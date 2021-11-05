<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Interaccion;
use App\Models\Ordenes;
use App\Models\Objetivo;
use App\Models\Funnel;
use App\Models\GeneracionDemanda;
use App\Models\Incidencia;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\RentabilidadPeriodosGastos;
use App\Models\RentabilidadGastos;
use App\Models\ErpTransaccion;

class DashboardEjecutivoController extends Controller
{
    public function dashboard_ejecutivo(Request $request)
    {
        $periodo=$request->periodo;//es el periodo del dashboard
        session()->put('periodo', $periodo);
        $nav_origen="PRINCIPAL";
        $dias_transcurridos=$this->getDiasTranscurridos($periodo);
        if(isset($request->tipo))
        {
            $nav_origen="DRILLDOWN";
            if($request->tipo=="E")
            {
                $origen='E';   
            }
            if($request->tipo=="G")
            {
                $origen='G';
                $filtro=$request->key;
                $titulo=$request->value;
            }
            if($request->tipo=="R")
            {
                $origen='R';
                $filtro=$request->value;
                $titulo=$request->value;
            }
        }
        else{
            if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
            {
                $origen='E';
            }
            if(Auth::user()->puesto=='Gerente')
            {
                $origen='G';
                $filtro=Auth::user()->udn;
                $titulo=Auth::user()->pdv;
            }
            if(Auth::user()->puesto=='Regional')
            {
                $origen='R';
                $filtro=Auth::user()->region;
                $titulo=Auth::user()->region;
            }
            if(Auth::user()->puesto=='Director')
            {
                $origen='D';
                $filtro='';
                $titulo='SUCURSALES';
            }
        }
        $indicadores=[];
        $detalles=[];
        if($origen=="D")
        {
            $detalles=[];
            $indicadores=$this->getIndicadores(0,0,$periodo,$dias_transcurridos);
            $regiones=Interaccion::select(DB::raw('distinct region'))
                        ->whereRaw('lpad(created_at,7,0)=?',[$periodo])
                        ->get();
            foreach($regiones as $region)
            {
                $detalles[]=[
                                'key'=>$region->region,
                                'value'=>$region->region,
                                'indicadores'=>$this->getIndicadores('REGION',$region->region,$periodo,$dias_transcurridos)
                            ];
            }
        }
        if($origen=="R")
        {
            $indicadores=$this->getIndicadores('REGION',$filtro,$periodo,$dias_transcurridos);
            $sucursales=Interaccion::select(DB::raw('distinct udn,pdv'))
                        ->where('region',$filtro)
                        ->whereRaw('lpad(created_at,7,0)=?',[$periodo])
                        ->get();
            foreach($sucursales as $sucursal)
            {
                $detalles[]=[
                                'key'=>$sucursal->udn,
                                'value'=>$sucursal->pdv,
                                'indicadores'=>$this->getIndicadores('UDN',$sucursal->udn,$periodo,$dias_transcurridos)
                            ];
            }
        }
        if($origen=="E" || $origen=="G")
        {
            $indicadores=$this->getIndicadores('UDN',$filtro,$periodo,$dias_transcurridos);
        }
        //return($detalles);
        return(view('dashboard_ejecutivo',[ 'periodo'=>$periodo,
                                            'nav_origen'=>$nav_origen,
                                            'origen'=>$origen,
                                            'indicadores'=>$indicadores,
                                            'detalles'=>$detalles,
                                            'titulo'=>$titulo,
                                        ]));
    }
    private function getDiasTranscurridos($periodo)
    {
        $sql_dia="select max(dia) as dia from estatico_dias where periodo='".$periodo."' and dia<=now()";
        $dia=collect(DB::select(DB::raw($sql_dia)))->first();
        return(explode("-",$dia->dia)[2]);
    }
    private function getIndicadores($campo_universo,$key_universo,$periodo,$dias_transcurridos)
    {
        $flujo=$this->getFlujo($campo_universo,$key_universo,$periodo);
        $cuotas=$this->getCuotas($campo_universo,$key_universo,$periodo,$dias_transcurridos);
        $ventas=$this->getVentas($campo_universo,$key_universo,$periodo);
        $productividad_total=$this->getResumenProductividad($campo_universo,$key_universo,$periodo,1);
        $productividad_q1=$this->getResumenProductividad($campo_universo,$key_universo,$periodo,2);
        $productividad_q2=$this->getResumenProductividad($campo_universo,$key_universo,$periodo,3);
        $productividad['total']=$productividad_total;
        $productividad['q1']=$productividad_q1;
        $productividad['q2']=$productividad_q2;
        $solicitudes=$this->getSolicitudes($campo_universo,$key_universo,$periodo);     
        $pendientes=$this->getPendientesFacturacion($campo_universo,$key_universo,$periodo);
        $demanda=$this->getDemanda($campo_universo,$key_universo,$periodo); 
        $activos=$this->getActivos($campo_universo,$key_universo,$periodo);
        $rentabilidad=$this->getRentabilidad($campo_universo,$key_universo,$periodo);
        $principal['flujo']=$flujo;
        $principal['cuotas']=$cuotas;
        $principal['ventas']=$ventas;
        $principal['productividad']=$productividad;
        $principal['solicitudes']=$solicitudes;
        $principal['pendientes']=$pendientes;
        $principal['demanda']=$demanda;
        $principal['activos']=$activos;
        $principal['rentabilidad']=$rentabilidad;
        return($principal);
    }
    private function getResumenProductividad($campo_universo,$key_universo,$periodo,$corte)
    {
        //SOLO SACA EL TOTAL y LA SEGUNDA QUINCENA
        $respuesta=array(
                    'minutos'=>0,
                    'incidencias'=>0
                    );
        if($corte==1)
        {
            $rango=$periodo."-1";
            $operador=">=";
        }
        if($corte==2)
        {
            $rango=$periodo."-16";
            $operador="<";
        }
        if($corte==3)
        {
            $rango=$periodo."-16";
            $operador=">=";
        }
        $sql_tiempos="
            select sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras from (
                SELECT dia as dia,0 as interaccion,0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras FROM `estatico_dias` WHERE dia<=now() and periodo='".$periodo."' and dia".$operador."'".$rango."'
                UNION
                select lpad(created_at,10,0) as dia,sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at".$operador."'".$rango."' group by lpad(created_at,10,0)
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at".$operador."'".$rango."' group by lpad(created_at,10,0)
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at".$operador."'".$rango."' group by lpad(created_at,10,0)
                UNION
                select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo".$operador."'".$rango."' group by dia_trabajo
                UNION
                select dia_incidencia as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' and dia_incidencia".$operador."'".$rango."' group by dia_incidencia
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."'  and created_at".$operador."'".$rango."' group by lpad(created_at,10,0)
                UNION
                select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo".$operador."'".$rango."' group by dia_trabajo
            )as a
        ";
        $minutos=collect(DB::select(DB::raw($sql_tiempos)))->first();
        $total_minutos=$minutos->interaccion+$minutos->funnel+$minutos->ordenes+$minutos->demanda+$minutos->otras;
        $respuesta['minutos']=$total_minutos;
        $respuesta['incidencias']=$minutos->incidencias*1;
        return($respuesta);
    }
    private function getPendientesFacturacion($campo_universo,$key_universo,$periodo)
    {
        $pendientes_q1=Ordenes::select(DB::raw('count(*) as n,sum(monto_total) as monto_total'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->where('estatus_final','ACEPTADA - Pendiente por facturar')
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $pendientes_total=Ordenes::select(DB::raw('count(*) as n,sum(monto_total) as monto_total'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->where('estatus_final','ACEPTADA - Pendiente por facturar')
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->get()
            ->first();
        $respuesta['pendientes_total']=$pendientes_total->n;
        $respuesta['monto_total']=$pendientes_total->monto_total;
        $respuesta['pendientes_q1']=$pendientes_q1->n;
        $respuesta['monto_total_q1']=$pendientes_q1->monto_total;
        $respuesta['pendientes_q2']=$pendientes_total->n-$pendientes_q1->n;
        $respuesta['monto_total_q2']=$pendientes_total->monto_total-$pendientes_q1->monto_total;
        return($respuesta);

    }
    private function getFlujo($campo_universo,$key_universo,$periodo)
    {
        $respuesta=array(
                        'flujo'=>0,
                        'flujo_intencion'=>0,
                        );
        $total_flujo=Interaccion::select(DB::raw("count(*) as visitas"))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->get()
            ->first();
        $flujo_intencion=Interaccion::select(DB::raw("count(*) as visitas"))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('intencion',1)
            ->get()
            ->first();
        $total_flujo_q1=Interaccion::select(DB::raw("count(*) as visitas"))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $flujo_intencion_q1=Interaccion::select(DB::raw("count(*) as visitas"))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('created_at','<=',$periodo.'-15')
            ->where('intencion',1)
            ->get()
            ->first();
        $respuesta['flujo']=$total_flujo->visitas;
        $respuesta['flujo_intencion']=$flujo_intencion->visitas;
        $respuesta['flujo_q1']=$total_flujo_q1->visitas;
        $respuesta['flujo_intencion_q1']=$flujo_intencion_q1->visitas;
        $respuesta['flujo_q2']=$total_flujo->visitas-$total_flujo_q1->visitas;
        $respuesta['flujo_intencion_q2']=$flujo_intencion->visitas-$flujo_intencion_q1->visitas;
        return($respuesta);
    }
    private function getCuotas($campo_universo,$key_universo,$periodo,$dias_transcurridos)
    {
        $cuotas=Objetivo::select(DB::raw("sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,sum(ac_q1) as ac_q1,sum(as_q1) as as_q1,sum(rc_q1) as rc_q1,sum(rs_q1) as rs_q1,sum(ac_q2) as ac_q2,sum(as_q2) as as_q2,sum(rc_q2) as rc_q2,sum(rs_q2) as rs_q2,".$dias_transcurridos."*sum(min_diario)*sum(ejecutivos) as minutos,".$dias_transcurridos."*sum(min_diario)*sum(ejecutivos)/2 as minutos_q1,".$dias_transcurridos."*sum(min_diario)*sum(ejecutivos)/2 as minutos_q2,sum(ejecutivos) as ejecutivos"))
            ->where('periodo',$periodo)
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->get()
            ->first();
        return($cuotas);
    }
    public function getVentas($campo_universo,$key_universo,$periodo)
    {
        $activaciones=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))        
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','like','Activacion%')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['act_total']=$activaciones->n;
        $respuesta['act_ticket_total']=$activaciones->n>0?$activaciones->rentas/$activaciones->n:0;
        $activaciones_q1=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))        
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','like','Activacion%')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['act_q1']=$activaciones_q1->n;
        $respuesta['act_ticket_q1']=$activaciones_q1->n>0?$activaciones_q1->rentas/$activaciones_q1->n:0;
        $respuesta['act_q2']=$activaciones->n-$activaciones_q1->n;
        $respuesta['act_ticket_q2']=$activaciones->n-$activaciones_q1->n>0?($activaciones->rentas-$activaciones_q1->rentas)/($activaciones->n-$activaciones_q1->n):0;
        $renovaciones=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))        
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','like','Renovacion%')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['ren_total']=$renovaciones->n;
        $respuesta['ren_ticket_total']=$renovaciones->n>0?$renovaciones->rentas/$renovaciones->n:0;
        $renovaciones_q1=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))        
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','like','Renovacion%')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['ren_q1']=$renovaciones_q1->n;
        $respuesta['ren_ticket_q1']=$renovaciones_q1->n>0?$renovaciones_q1->rentas/$renovaciones_q1->n:0;
        $respuesta['ren_q2']=$renovaciones->n-$renovaciones_q1->n;
        $respuesta['ren_ticket_q2']=$renovaciones->n-$renovaciones_q1->n>0?($renovaciones->rentas-$renovaciones_q1->rentas)/($renovaciones->n-$renovaciones_q1->n):0;

        $activaciones_con=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))        
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Activacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['act_con_total']=$activaciones_con->n;
        $respuesta['act_con_ticket_total']=$activaciones_con->n>0?$activaciones_con->rentas/$activaciones_con->n:0;
        $activaciones_sin=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Activacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['act_sin_total']=$activaciones_sin->n;
        $respuesta['act_sin_ticket_total']=$activaciones_sin->n>0?$activaciones_sin->rentas/$activaciones_sin->n:0;
        $renovaciones_con=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Renovacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['ren_con_total']=$renovaciones_con->n;
        $respuesta['ren_con_ticket_total']=$renovaciones_con->n>0?$renovaciones_con->rentas/$renovaciones_con->n:0;
        $renovaciones_sin=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Renovacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['ren_sin_total']=$renovaciones_sin->n;
        $respuesta['ren_sin_ticket_total']=$renovaciones_sin->n>0?$renovaciones_sin->rentas/$renovaciones_sin->n:0;
        $activaciones_con_q1=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Activacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['act_con_q1']=$activaciones_con_q1->n;
        $respuesta['act_con_ticket_q1']=$activaciones_con_q1->n>0?$activaciones_con_q1->rentas/$activaciones_con_q1->n:0;
        $respuesta['act_con_q2']=$activaciones_con->n-$activaciones_con_q1->n;
        $respuesta['act_con_ticket_q2']=($activaciones_con->n-$activaciones_con_q1->n)>0?($activaciones_con->rentas-$activaciones_con_q1->rentas)/($activaciones_con->n-$activaciones_con_q1->n):0;
        $activaciones_sin_q1=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Activacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['act_sin_q1']=$activaciones_sin_q1->n;
        $respuesta['act_sin_ticket_q1']=$activaciones_sin_q1->n>0?$activaciones_sin_q1->rentas/$activaciones_sin_q1->n:0;
        $respuesta['act_sin_q2']=$activaciones_sin->n-$activaciones_sin_q1->n;
        $respuesta['act_sin_ticket_q2']=($activaciones_sin->n-$activaciones_sin_q1->n)>0?($activaciones_sin->rentas-$activaciones_sin_q1->rentas)/($activaciones_sin->n-$activaciones_sin_q1->n):0;
        $renovaciones_con_q1=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Renovacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['ren_con_q1']=$renovaciones_con_q1->n;
        $respuesta['ren_con_ticket_q1']=$renovaciones_con_q1->n>0?$renovaciones_con_q1->rentas/$renovaciones_con_q1->n:0;
        $respuesta['ren_con_q2']=$renovaciones_con->n-$renovaciones_con_q1->n;
        $respuesta['ren_con_ticket_q2']=($renovaciones_con->n-$renovaciones_con_q1->n)>0?($renovaciones_con->rentas-$renovaciones_con_q1->rentas)/($renovaciones_con->n-$renovaciones_con_q1->n):0;
        $renovaciones_sin_q1=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('producto','Renovacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['ren_sin_q1']=$renovaciones_sin_q1->n;
        $respuesta['ren_sin_ticket_q1']=$renovaciones_sin_q1->n>0?$renovaciones_sin_q1->rentas/$renovaciones_sin_q1->n:0;
        $respuesta['ren_sin_q2']=$renovaciones_sin->n-$renovaciones_sin_q1->n;
        $respuesta['ren_sin_ticket_q2']=($renovaciones_sin->n-$renovaciones_sin_q1->n)>0?($renovaciones_sin->rentas-$renovaciones_sin_q1->rentas)/($renovaciones_sin->n-$renovaciones_sin_q1->n):0;
        $activaciones_con_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Activacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['act_con_tda_total']=$activaciones_con_tda->n;
        $respuesta['act_con_tda_ticket_total']=$activaciones_con_tda->n>0?$activaciones_con_tda->rentas/$activaciones_con_tda->n:0;
        $respuesta['act_con_dem_total']=$activaciones_con->n-$activaciones_con_tda->n;
        $respuesta['act_con_dem_ticket_total']=($activaciones_con->n-$activaciones_con_tda->n)>0?($activaciones_con->rentas-$activaciones_con_tda->rentas)/(($activaciones_con->n-$activaciones_con_tda->n)):0;
        $activaciones_sin_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Activacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['act_sin_tda_total']=$activaciones_sin_tda->n;
        $respuesta['act_sin_tda_ticket_total']=$activaciones_sin_tda->n>0?$activaciones_sin_tda->rentas/$activaciones_sin_tda->n:0;
        $respuesta['act_sin_dem_total']=$activaciones_sin->n-$activaciones_sin_tda->n;
        $respuesta['act_sin_dem_ticket_total']=($activaciones_sin->n-$activaciones_sin_tda->n)>0?($activaciones_sin->rentas-$activaciones_sin_tda->rentas)/(($activaciones_sin->n-$activaciones_sin_tda->n)):0;
        $renovaciones_con_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Renovacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['ren_con_tda_total']=$renovaciones_con_tda->n;
        $respuesta['ren_con_tda_ticket_total']=$renovaciones_con_tda->n>0?$renovaciones_con_tda->rentas/$renovaciones_con_tda->n:0;
        $respuesta['ren_con_dem_total']=$renovaciones_con->n-$renovaciones_con_tda->n;
        $respuesta['ren_con_dem_ticket_total']=($renovaciones_con->n-$renovaciones_con_tda->n)>0?($renovaciones_con->rentas-$renovaciones_con_tda->rentas)/(($renovaciones_con->n-$renovaciones_con_tda->n)):0;
        $renovaciones_sin_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Renovacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->get()
            ->first();
        $respuesta['ren_sin_tda_total']=$renovaciones_sin_tda->n;
        $respuesta['ren_sin_tda_ticket_total']=$renovaciones_sin_tda->n>0?$renovaciones_sin_tda->rentas/$renovaciones_sin_tda->n:0;
        $respuesta['ren_sin_dem_total']=$renovaciones_sin->n-$renovaciones_sin_tda->n;
        $respuesta['ren_sin_dem_ticket_total']=($renovaciones_sin->n-$renovaciones_sin_tda->n)>0?($renovaciones_sin->rentas-$renovaciones_sin_tda->rentas)/(($renovaciones_sin->n-$renovaciones_sin_tda->n)):0;
        $activaciones_con_q1_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Activacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['act_con_tda_q1']=$activaciones_con_q1_tda->n;
        $respuesta['act_con_tda_ticket_q1']=$activaciones_con_q1_tda->n>0?$activaciones_con_q1_tda->rentas/$activaciones_con_q1_tda->n:0;
        $respuesta['act_con_tda_q2']=$activaciones_con_tda->n-$activaciones_con_q1_tda->n;
        $respuesta['act_con_tda_ticket_q2']=($activaciones_con_tda->n-$activaciones_con_q1_tda->n)>0?($activaciones_con_tda->rentas-$activaciones_con_q1_tda->rentas)/($activaciones_con_tda->n-$activaciones_con_q1_tda->n):0;
        $total_q1=$respuesta['act_con_q1'];
        $total_q2=$respuesta['act_con_q2'];
        $rentas_total_q1=$respuesta['act_con_ticket_q1']*$respuesta['act_con_q1'];
        $rentas_total_q2=$respuesta['act_con_ticket_q2']*$respuesta['act_con_q2'];
        $unidades_tda_q1=$respuesta['act_con_tda_q1'];
        $unidades_tda_q2=$respuesta['act_con_tda_q2'];
        $rentas_tda_q1=$respuesta['act_con_tda_ticket_q1']*$respuesta['act_con_tda_q1'];
        $rentas_tda_q2=$respuesta['act_con_tda_ticket_q2']*$respuesta['act_con_tda_q2'];
        $respuesta['act_con_dem_q1']=$total_q1-$unidades_tda_q1;
        $respuesta['act_con_dem_ticket_q1']=$total_q1-$unidades_tda_q1>0?($rentas_total_q1-$rentas_tda_q1)/($total_q1-$unidades_tda_q1):0;
        $respuesta['act_con_dem_q2']=$total_q2-$unidades_tda_q2;
        $respuesta['act_con_dem_ticket_q2']=$total_q2-$unidades_tda_q2>0?($rentas_total_q2-$rentas_tda_q2)/($total_q2-$unidades_tda_q2):0;
        
        $activaciones_sin_q1_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Activacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['act_sin_tda_q1']=$activaciones_sin_q1_tda->n;
        $respuesta['act_sin_tda_ticket_q1']=$activaciones_sin_q1_tda->n>0?$activaciones_sin_q1_tda->rentas/$activaciones_sin_q1_tda->n:0;
        $respuesta['act_sin_tda_q2']=$activaciones_sin_tda->n-$activaciones_sin_q1_tda->n;
        $respuesta['act_sin_tda_ticket_q2']=($activaciones_sin_tda->n-$activaciones_sin_q1_tda->n)>0?($activaciones_sin_tda->rentas-$activaciones_sin_q1_tda->rentas)/($activaciones_sin_tda->n-$activaciones_sin_q1_tda->n):0;
        $total_q1=$respuesta['act_sin_q1'];
        $total_q2=$respuesta['act_sin_q2'];
        $rentas_total_q1=$respuesta['act_sin_ticket_q1']*$respuesta['act_sin_q1'];
        $rentas_total_q2=$respuesta['act_sin_ticket_q2']*$respuesta['act_sin_q2'];
        $unidades_tda_q1=$respuesta['act_sin_tda_q1'];
        $unidades_tda_q2=$respuesta['act_sin_tda_q2'];
        $rentas_tda_q1=$respuesta['act_sin_tda_ticket_q1']*$respuesta['act_sin_tda_q1'];
        $rentas_tda_q2=$respuesta['act_sin_tda_ticket_q2']*$respuesta['act_sin_tda_q2'];
        $respuesta['act_sin_dem_q1']=$total_q1-$unidades_tda_q1;
        $respuesta['act_sin_dem_ticket_q1']=$total_q1-$unidades_tda_q1>0?($rentas_total_q1-$rentas_tda_q1)/($total_q1-$unidades_tda_q1):0;
        $respuesta['act_sin_dem_q2']=$total_q2-$unidades_tda_q2;
        $respuesta['act_sin_dem_ticket_q2']=$total_q2-$unidades_tda_q2>0?($rentas_total_q2-$rentas_tda_q2)/($total_q2-$unidades_tda_q2):0;
        $renovaciones_con_q1_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Renovacion CON equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['ren_con_tda_q1']=$renovaciones_con_q1_tda->n;
        $respuesta['ren_con_tda_ticket_q1']=$renovaciones_con_q1_tda->n>0?$renovaciones_con_q1_tda->rentas/$renovaciones_con_q1_tda->n:0;
        $respuesta['ren_con_tda_q2']=$renovaciones_con_tda->n-$renovaciones_con_q1_tda->n;
        $respuesta['ren_con_tda_ticket_q2']=($renovaciones_con_tda->n-$renovaciones_con_q1_tda->n)>0?($renovaciones_con_tda->rentas-$renovaciones_con_q1_tda->rentas)/($renovaciones_con_tda->n-$renovaciones_con_q1_tda->n):0;
        $total_q1=$respuesta['ren_con_q1'];
        $total_q2=$respuesta['ren_con_q2'];
        $rentas_total_q1=$respuesta['ren_con_ticket_q1']*$respuesta['ren_con_q1'];
        $rentas_total_q2=$respuesta['ren_con_ticket_q2']*$respuesta['ren_con_q2'];
        $unidades_tda_q1=$respuesta['ren_con_tda_q1'];
        $unidades_tda_q2=$respuesta['ren_con_tda_q2'];
        $rentas_tda_q1=$respuesta['ren_con_tda_ticket_q1']*$respuesta['ren_con_tda_q1'];
        $rentas_tda_q2=$respuesta['ren_con_tda_ticket_q2']*$respuesta['ren_con_tda_q2'];
        $respuesta['ren_con_dem_q1']=$total_q1-$unidades_tda_q1;
        $respuesta['ren_con_dem_ticket_q1']=$total_q1-$unidades_tda_q1>0?($rentas_total_q1-$rentas_tda_q1)/($total_q1-$unidades_tda_q1):0;
        $respuesta['ren_con_dem_q2']=$total_q2-$unidades_tda_q2;
        $respuesta['ren_con_dem_ticket_q2']=$total_q2-$unidades_tda_q2>0?($rentas_total_q2-$rentas_tda_q2)/($total_q2-$unidades_tda_q2):0;
        $renovaciones_sin_q1_tda=Ordenes::select(DB::raw('count(*) as n,sum(renta) as rentas'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('producto','Renovacion SIN equipo')
            ->where('estatus_final','ACEPTADA - Facturada')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['ren_sin_tda_q1']=$renovaciones_sin_q1_tda->n;
        $respuesta['ren_sin_tda_ticket_q1']=$renovaciones_sin_q1_tda->n>0?$renovaciones_sin_q1_tda->rentas/$renovaciones_sin_q1_tda->n:0;
        $respuesta['ren_sin_tda_q2']=$renovaciones_sin_tda->n-$renovaciones_sin_q1_tda->n;
        $respuesta['ren_sin_tda_ticket_q2']=($renovaciones_sin_tda->n-$renovaciones_sin_q1_tda->n)>0?($renovaciones_sin_tda->rentas-$renovaciones_sin_q1_tda->rentas)/($renovaciones_sin_tda->n-$renovaciones_sin_q1_tda->n):0;
        $total_q1=$respuesta['ren_sin_q1'];
        $total_q2=$respuesta['ren_sin_q2'];
        $rentas_total_q1=$respuesta['ren_sin_ticket_q1']*$respuesta['ren_sin_q1'];
        $rentas_total_q2=$respuesta['ren_sin_ticket_q2']*$respuesta['ren_sin_q2'];
        $unidades_tda_q1=$respuesta['ren_sin_tda_q1'];
        $unidades_tda_q2=$respuesta['ren_sin_tda_q2'];
        $rentas_tda_q1=$respuesta['ren_sin_tda_ticket_q1']*$respuesta['ren_sin_tda_q1'];
        $rentas_tda_q2=$respuesta['ren_sin_tda_ticket_q2']*$respuesta['ren_sin_tda_q2'];
        $respuesta['ren_sin_dem_q1']=$total_q1-$unidades_tda_q1;
        $respuesta['ren_sin_dem_ticket_q1']=$total_q1-$unidades_tda_q1>0?($rentas_total_q1-$rentas_tda_q1)/($total_q1-$unidades_tda_q1):0;
        $respuesta['ren_sin_dem_q2']=$total_q2-$unidades_tda_q2;
        $respuesta['ren_sin_dem_ticket_q2']=$total_q2-$unidades_tda_q2>0?($rentas_total_q2-$rentas_tda_q2)/($total_q2-$unidades_tda_q2):0;
        return($respuesta);
    }
    private function getSolicitudes($campo_universo,$key_universo,$periodo)
    {
        $solicitudes_tienda_total=Ordenes::select(DB::raw('count(*) as n'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->get()
            ->first();
        $respuesta['tda_total']=$solicitudes_tienda_total->n;
        $solicitudes_tienda_q1=Ordenes::select(DB::raw('count(*) as n'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','Tienda')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['tda_q1']=$solicitudes_tienda_q1->n;
        $respuesta['tda_q2']=$solicitudes_tienda_total->n-$solicitudes_tienda_q1->n;
        $solicitudes_generacion_total=Ordenes::select(DB::raw('count(*) as n'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','!=','Tienda')
            ->get()
            ->first();
        $respuesta['dem_total']=$solicitudes_generacion_total->n;
        $solicitudes_generacion_q1=Ordenes::select(DB::raw('count(*) as n'))
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('origen','!=','Tienda')
            ->where('created_at','<=',$periodo.'-15')
            ->get()
            ->first();
        $respuesta['dem_q1']=$solicitudes_generacion_q1->n;
        $respuesta['dem_q2']=$solicitudes_generacion_total->n-$solicitudes_generacion_q1->n;
        return($respuesta);
    }
    private function getDemanda($campo_universo,$key_universo,$periodo)
    {
        $demanda=GeneracionDemanda::select(DB::raw('sum(sms+sms_individual+llamadas+rs) as n'))
            ->whereRaw('lpad(dia_trabajo,7,0)=?',[$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->get()
            ->first();
        $demanda_q1=GeneracionDemanda::select(DB::raw('sum(sms+sms_individual+llamadas+rs) as n'))
            ->whereRaw('lpad(dia_trabajo,7,0)=?',[$periodo])
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('dia_trabajo','<=',$periodo.'-15')
            ->first();
        $respuesta['demanda_total']=$demanda->n;
        $respuesta['demanda_q1']=$demanda_q1->n;
        $respuesta['demanda_q2']=$demanda->n-$demanda_q1->n;   
        return($respuesta);
    }
    private function getActivos($campo_universo,$key_universo,$periodo)
    {
        $activos=User::select(DB::raw('count(*) as n'))
            ->where('estatus','Activo')
            ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
            ->where('puesto','Ejecutivo')
            ->get()
            ->first();
        $respuesta['activos']=$activos->n;
        return($respuesta);
    }
    private function getRentabilidad($campo_universo,$key_universo,$periodo)
    {
        $consistencia=false;
        //CON ESTO OBTIENE EL PERIODO QUE LE TOCA
        $periodo_gastos=RentabilidadPeriodosGastos::withCount('detalles')
                                        ->where('inicio_vigencia','<=',$periodo.'-01')
                                        ->where('fin_vigencia','>=',$periodo.'-01')
                                        ->get()
                                        ->first();
        $id_gastos=$periodo_gastos->id;
        $titulo_gastos=$periodo_gastos->descripcion;
        //VERIFICA QUE YA ESTAN CARGADOS SUS PARAMETROS
        if($periodo_gastos->detalles_count==0)
        {
            $ultimo_conocido=RentabilidadGastos::select(DB::raw('max(periodo) as id'))
                                                ->get()
                                                ->first();
            $periodo_alterno=RentabilidadPeriodosGastos::find($ultimo_conocido->id);
            $id_gastos=$ultimo_conocido->id;
            $titulo_gastos=$periodo_alterno->descripcion;
            $consistencia=false;
            //return($ultimo_conocido);
        }
        else{
            $consistencia=true;
        }
        $gastos=RentabilidadGastos::where('periodo',$id_gastos)
                        ->select(DB::raw('sum(gastos_fijos) as g_f,sum(gastos_indirectos) as g_i'))
                        ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
                        ;
        $erp=ErpTransaccion::whereRaw('lpad(fecha,7,0)=?',$periodo)
                        ->select(DB::raw('sum(ingreso) as ingreso,sum(costo_venta) as c_v'))
                        ->when($campo_universo!="0",function($query) use ($campo_universo,$key_universo){$query->where($campo_universo,$key_universo);})
                        ;
        $gastos=$gastos->get()->first();
        $erp=$erp->get()->first();
        $respuesta['g_fijos']=$gastos->g_f;
        $respuesta['g_indirectos']=$gastos->g_i;
        $respuesta['c_venta']=$erp->c_v;
        $respuesta['ingresos']=$erp->ingreso;
        $respuesta['consistencia']=$consistencia?"SI":"NO";
        $respuesta['leyenda_gastos']=$titulo_gastos;
        $respuesta['rentabilidad']=($gastos->g_f+$gastos->g_i+$erp->c_v)>0?100*$erp->ingreso/(($gastos->g_f+$gastos->g_i+$erp->c_v)):0;
        return($respuesta);
    }
}
