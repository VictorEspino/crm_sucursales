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
use App\Models\EstaticoDias;
use App\Models\RentabilidadPeriodosGastos;
use App\Models\RentabilidadGastos;
use App\Models\ErpTransaccion;

class DashboardsController extends Controller
{
    public static function alert_objetivos()
    {
        if(Auth::user()->puesto=='Regional' || Auth::user()->puesto=='Director' )
            {return("0");}
        else
            {
                $periodo_actual=substr(now(),0,7);
                try{
                    $sucursal_medida=Objetivo::where('udn',Auth::user()->udn)->where('periodo',$periodo_actual)->get()->first();
                    $minutos=$sucursal_medida->min_diario;
                    return("0");
                }
                catch(\Exception $e)
                {
                    $minutos=360;
                    return("1");
                }
            }
    }
    public static function gauge($tipo)
    {
        if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
        {
            $campo_universo='empleado';
            $key_universo=Auth::user()->empleado;
            $titulo=Auth::user()->name;
            $origen='E';
        }
        if(Auth::user()->puesto=='Gerente')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
            $titulo=Auth::user()->pdv;
            $origen='G';
            $campos_group='a.empleado,a.nombre';
            $campos_vista="empleado as llave,nombre as value";
        }
        if(Auth::user()->puesto=='Regional')
        {
            $campo_universo='region';
            $key_universo=Auth::user()->pdv;
            $titulo=Auth::user()->region;
            $origen='R';
            $campos_group='a.udn,a.pdv';
            $campos_vista="udn as llave,pdv as value";
        }
        if(Auth::user()->puesto=='Director')
        {
            $campo_universo=0;
            $key_universo=0;
            $titulo='Sucursales';
            $origen='D';
            $campos_group='a.region';
            $campos_vista="region as llave,region as value";
        }
        $ordenes_tienda=Ordenes::select(DB::raw('count(*) as facturadas'))
                ->whereRaw(''.$campo_universo.'=?',$key_universo)
                ->where('estatus_final','ACEPTADA - Facturada')
                ->where('origen','Tienda')
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();
        $ordenes_demanda=Ordenes::select(DB::raw('count(*) as facturadas'))
                ->whereRaw(''.$campo_universo.'=?',$key_universo)
                ->where('estatus_final','ACEPTADA - Facturada')
                ->where('origen','!=','Tienda')
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();

        if($tipo=="1")
        {
            $visitas=Interaccion::select(DB::raw('count(*) as visitas'))
                ->whereRaw(''.$campo_universo.'=?',$key_universo)
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();
            if($visitas->visitas!="0")
            {
                return(number_format(100*$ordenes_tienda->facturadas/$visitas->visitas,0));
            }
            else
            {
                return(0);
            }
        }
        if($tipo=="2")
        {
            $ordenes_total=Ordenes::select(DB::raw('count(*) as total'))
                ->whereRaw(''.$campo_universo.'=?',$key_universo)
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();
            if($ordenes_total->total!="0")
            {
                return(number_format(100*($ordenes_tienda->facturadas+$ordenes_demanda->facturadas)/$ordenes_total->total,0));
            }
            else
            {
                return(0);
            }
        }
        if($tipo=="3")
        {
            $visitas=Interaccion::select(DB::raw('count(*) as visitas'))
                ->whereRaw(''.$campo_universo.'=?',$key_universo)
                ->where('intencion',1)
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();
            if($visitas->visitas!="0")
            {
                return(number_format(100*$ordenes_tienda->facturadas/$visitas->visitas,0));
            }
            else
            {
                return(0);
            }
        }

        
    }
    public function objetivo_form(Request $request)
    {
        $periodos=DB::select(DB::raw(
            "select distinct periodo from estatico_dias where dia<=now() order by periodo desc"
        ));
        $periodos=collect($periodos)->take(8);
        return(view('modificar_objetivo',['periodos'=>$periodos]));
    }
    public function dashboard_central()
    {
        $periodos=DB::select(DB::raw(
            "select distinct periodo from estatico_dias where dia<=now() order by periodo desc"
        ));
        $periodos=collect($periodos)->take(8);
        if(!session()->has('periodo'))
        {
            $ciclo=1;
            foreach($periodos as $periodo)
            {
                if($ciclo==1)
                {session()->put('periodo', $periodo->periodo);}
                $ciclo=$ciclo+1;
            }
        }
        return view('dashboard',['periodos'=>$periodos]);


        
    }
    public function dashboard_efectividad(Request $request)
    {
        
        $periodo=$request->periodo;
        session()->put('periodo', $periodo);
        $campo_universo='';
        $key_universo='';
        $titulo='';
        $origen='';
        $ac=0;
        $as=0;
        $rc=0;
        $rs=0;
        $campos_group="";
        $nav_origen="PRINCIPAL";
        if(isset($request->tipo))
        {
            $nav_origen="DRILLDOWN";
            if($request->tipo=="E")
            {
                $campo_universo='empleado';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='E';
            }
            if($request->tipo=="G")
            {
                $campo_universo='udn';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='G';
                $campos_group='a.empleado,a.nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if($request->tipo=="R")
            {
                $campo_universo='region';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='R';
                $campos_group='a.udn,a.pdv';
                $campos_vista="udn as llave,pdv as value";
            }
        }
        else{
            if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
            {
                $campo_universo='empleado';
                $key_universo=Auth::user()->empleado;
                $titulo=Auth::user()->name;
                $origen='E';
            }
            if(Auth::user()->puesto=='Gerente')
            {
                $campo_universo='udn';
                $key_universo=Auth::user()->udn;
                $titulo=Auth::user()->pdv;
                $origen='G';
                $campos_group='a.empleado,a.nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if(Auth::user()->puesto=='Regional')
            {
                $campo_universo='region';
                $key_universo=Auth::user()->pdv;
                $titulo=Auth::user()->region;
                $origen='R';
                $campos_group='a.udn,a.pdv';
                $campos_vista="udn as llave,pdv as value";
            }
            if(Auth::user()->puesto=='Director')
            {
                $campo_universo=0;
                $key_universo=0;
                $titulo='Sucursales';
                $origen='D';
                $campos_group='a.region';
                $campos_vista="region as llave,region as value";
            }
        }
        $query_visitas=Interaccion::select(DB::raw("count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_intencion=Interaccion::select(DB::raw("count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('intencion',1)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_solicitudes=Interaccion::select(DB::raw("count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('fin_interaccion','Orden')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_aprobadas=Ordenes::select(DB::raw("count(*) as ordenes"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('estatus_final','like','ACEPTADA%')
            ->where('origen','Tienda')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_facturadas=Ordenes::select(DB::raw("count(*) as ordenes"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('estatus_final','like','ACEPTADA - F%')
            ->where('origen','Tienda')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_productos=Ordenes::select(DB::raw('producto,count(*) as ordenes'))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('estatus_final','like','ACEPTADA - F%')
            ->where('origen','Tienda')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('producto')
            ->get();
        foreach ($query_productos as $producto) {
            if($producto->producto=='Activacion CON equipo'){$ac=$producto->ordenes;}
            if($producto->producto=='Activacion SIN equipo'){$as=$producto->ordenes;}
            if($producto->producto=='Renovacion CON equipo'){$rc=$producto->ordenes;}
            if($producto->producto=='Renovacion SIN equipo'){$rs=$producto->ordenes;}
        }
            
        $c_ac=0;
        $c_as=0;
        $c_rc=0;
        $c_rs=0;
        $ejecutivos=1;
        if($origen=="E") 
        //SE UTILIZA EMERGENTE PARA LA AGRUPACION DE OBJETIVOS
        {
            $campo_universo='udn';
            $usuario_consultado=User::where('empleado',$key_universo)->get()->first();
            $key_universo=$usuario_consultado->udn;         
        }
        
        
        try{
        $query_cuotas=[];
        if($origen!="D")
        {
        $query_cuotas=Objetivo::select(DB::raw($campo_universo.",sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,sum(ejecutivos) as ejecutivos"))
            ->where('periodo',$periodo)
            ->where($campo_universo,$key_universo)
            ->groupBy($campo_universo)
            ->get()
            ->first();
        }
        else {
            $query_cuotas=Objetivo::select(DB::raw('sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,sum(ejecutivos) as ejecutivos'))
            ->where('periodo',$periodo)
            ->get()
            ->first();
        }

        $c_ac=$query_cuotas->ac;
        $c_as=$query_cuotas->asi;
        $c_rc=$query_cuotas->rc;
        $c_rs=$query_cuotas->rs;
        $ejecutivos=$query_cuotas->ejecutivos;
        }
        catch(\Exception $e)
        {
            $c_ac=0;
            $c_as=0;
            $c_rc=0;
            $c_rs=0;
            $ejecutivos=1;
        }

        if($origen=="E") 
        {
            $c_ac=$c_ac/$ejecutivos;
            $c_as=$c_as/$ejecutivos;
            $c_rc=$c_rc/$ejecutivos;
            $c_rs=$c_rs/$ejecutivos;
        }
        try{
            $a_ac=$c_ac!=0?100*$ac/$c_ac:0;
            $a_as=$c_as!=0?100*$as/$c_as:0;
            $a_rc=$c_rc!=0?100*$rc/$c_rc:0;
            $a_rs=$c_rs!=0?100*$rs/$c_rs:0;
        }
        catch(\Exception $e)
        {
            $a_ac=0;
            $a_as=0;
            $a_rc=0;
            $a_rs=0;   
        }

        $visitas=$query_visitas->visitas;
        $intencion=$query_intencion->visitas;
        $solicitudes=$query_solicitudes->visitas;
        $aprobadas=$query_aprobadas->ordenes;
        $facturadas=$query_facturadas->ordenes;
        $total=$ac+$as+$rc+$rs;
        $ev=0;
        $ei=0;
        if($visitas!=0)
        {
            $ev=100*$facturadas/$visitas;
        }
        else
        {
            $ev='0';
        }
        if($intencion!=0)
        {
            $ei=100*$facturadas/$intencion;
        }
        else
        {
            $ei='0';
        }

        //OBTIENE TABLA DE DETALLES
        $details=0;
        if($origen=='G'||$origen=='R'||$origen=='D')
        {
            $sql_detalles="select ".$campos_vista.",visitas,intencion,facturadas from (select ".$campos_group.",sum(visitas) as visitas,sum(intencion) as intencion,sum(facturadas) as facturadas from (";
            $sql_detalles=$sql_detalles."select ".$campos_group.",count(*) as visitas,0 as intencion,0 as facturadas  from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."'  group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."select ".$campos_group.",0 as visitas,count(*) as intencion,0 as facturadas  from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and intencion=1 group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."SELECT ".$campos_group.",0 as visitas,0 as intencion, count(*) as facturadas from ordenes as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and estatus_final='ACEPTADA - Facturada' and origen='Tienda' group by ".$campos_group;
            $sql_detalles=$sql_detalles.") as a group by ".$campos_group.") as a order by a.facturadas desc";
            
            $details=DB::select(DB::raw(
                $sql_detalles
            ));
        }

        
        
        return(view('dashboard_efectividad',['titulo' => $titulo,
                                             'periodo' => $periodo,
                                             'visitas'=> $visitas,
                                             'intencion'=>$intencion,
                                             'solicitudes'=>$solicitudes,
                                             'aprobadas'=>$aprobadas,
                                             'facturadas'=>$facturadas,
                                             'ac'=>$ac,
                                             'c_ac'=>$c_ac,
                                             'a_ac'=>$a_ac,
                                             'as'=>$as,
                                             'c_as'=>$c_as,
                                             'a_as'=>$a_as,
                                             'rc'=>$rc,
                                             'c_rc'=>$c_rc,
                                             'a_rc'=>$a_rc,
                                             'rs'=>$rs,
                                             'c_rs'=>$c_rs,
                                             'a_rs'=>$a_rs,
                                             'total'=>$total,
                                             'ev'=>$ev,
                                             'ei'=>$ei,
                                             'origen'=>$origen,
                                             'nav_origen'=>$nav_origen,
                                             'details'=>$details
                    ]));
    }
    public function dashboard_interaccion(Request $request)
    {
        $periodo=$request->periodo;
        session()->put('periodo', $periodo);
        $campo_universo='';
        $key_universo='';
        $titulo='';
        $origen='';
        $campos_group="";
        $nav_origen="PRINCIPAL";
        if(isset($request->tipo))
        {
            $nav_origen="DRILLDOWN";
            if($request->tipo=="E")
            {
                $campo_universo='empleado';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='E';
            }
            if($request->tipo=="G")
            {
                $campo_universo='udn';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='G';
                $campos_group='a.empleado,a.nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if($request->tipo=="R")
            {
                $campo_universo='region';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='R';
                $campos_group='a.udn,a.pdv';
                $campos_vista="udn as llave,pdv as value";
            }
        }
        else{
            if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
            {
                $campo_universo='empleado';
                $key_universo=Auth::user()->empleado;
                $titulo=Auth::user()->name;
                $origen='E';
            }
            if(Auth::user()->puesto=='Gerente')
            {
                $campo_universo='udn';
                $key_universo=Auth::user()->udn;
                $titulo=Auth::user()->pdv;
                $origen='G';
                $campos_group='a.empleado,a.nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if(Auth::user()->puesto=='Regional')
            {
                $campo_universo='region';
                $key_universo=Auth::user()->pdv;
                $titulo=Auth::user()->region;
                $origen='R';
                $campos_group='a.udn,a.pdv';
                $campos_vista="udn as llave,pdv as value";
            }
            if(Auth::user()->puesto=='Director')
            {
                $campo_universo=0;
                $key_universo=0;
                $titulo='Sucursales';
                $origen='D';
                $campos_group='a.region';
                $campos_vista="region as llave,region as value";
            }
        }

        $ci=0;
        $si=0;
        $pi=0;
        $ps=0;
        $total=0;
        $query_intencion=Interaccion::select(DB::raw("intencion,count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('intencion')
            ->get();

        $sql_diario="select dia,sum(intencion) as intencion,sum(sin_intencion) as sin_intencion from (
            select lpad(created_at,10,0) as dia,'1' as fuente,count(*) as intencion,0 as sin_intencion from `interaccions` where ".$campo_universo."='".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and intencion=1 group by lpad(created_at,10,0),intencion
            UNION
            select lpad(created_at,10,0) as dia,'2' as fuente,0 as intencion,count(*) as sin_intencion from `interaccions` where ".$campo_universo."='".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and intencion=0 group by lpad(created_at,10,0),intencion
            UNION
            select dia,'3' as fuente,0 as intencion,0 as sin_intencion from estatico_dias where periodo='".$periodo."'
            UNION
            select dia,'4' as fuente,0 as intencion,0 as sin_intencion from estatico_dias where periodo='".$periodo."'
            ) as a group by a.dia";
        $flujo_diario=DB::select(DB::raw(
                $sql_diario
            ));
        $flujo_diario=collect($flujo_diario);
        //return($flujo_diario);
        
        
        //return($query_intencion);
        foreach($query_intencion as $intencion)
        {
            $total=$total+$intencion->visitas;
            if($intencion->intencion=="0")
            {
                $si=$si+$intencion->visitas;
            }
            if($intencion->intencion=="1")
            {
                $ci=$ci+$intencion->visitas;
            }
        }
        if($total>0)
        {
            $pi=100*$ci/$total;
            $ps=100*$si/$total;
        }

        
        $query_tramite_c=Interaccion::select(DB::raw("tramite,count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('intencion','1')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('tramite')
            ->get();
        $query_fin_c=Interaccion::select(DB::raw("fin_interaccion,count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('intencion','1')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('fin_interaccion')
            ->get();
        $query_tramite_s=Interaccion::select(DB::raw("tramite,count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('intencion','0')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('tramite')
            ->get();
        $query_fin_s=Interaccion::select(DB::raw("fin_interaccion,count(*) as visitas"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('intencion','0')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('fin_interaccion')
            ->get();

        //OBTIENE TABLA DE DETALLES
        $details=0;
        if($origen=='G'||$origen=='R'||$origen=="D")
        {
            $sql_detalles="select ".$campos_vista.",sin_intencion,intencion,total from (select ".$campos_group.",sum(sin_intencion) as sin_intencion,sum(intencion) as intencion,sum(total) as total from (";
            $sql_detalles=$sql_detalles."select ".$campos_group.",count(*) as sin_intencion,0 as intencion,0 as total from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and intencion=0 group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."select ".$campos_group.",0 as sin_intencion,count(*) as intencion,0 as total from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and intencion=1 group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."select ".$campos_group.",0 as sin_intencion,0 as intencion,count(*) as total from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' group by ".$campos_group;
            $sql_detalles=$sql_detalles.") as a group by ".$campos_group.") as a order by a.total desc";
            $details=DB::select(DB::raw(
                $sql_detalles
            ));
        }

        return (view('dashboard_interaccion',['periodo'=>$periodo,
                                              'origen'=>$origen,
                                              'nav_origen'=>$nav_origen,
                                              'titulo'=>$titulo,
                                              'ci'=>$ci,
                                              'si'=>$si,
                                              'pi'=>$pi,
                                              'ps'=>$ps,
                                              'total'=>$total,
                                              'tramites_con'=>$query_tramite_c,
                                              'fin_con'=>$query_fin_c,
                                              'tramites_sin'=>$query_tramite_s,
                                              'fin_sin'=>$query_fin_s,
                                              'details'=>$details,
                                              'diario'=>$flujo_diario
                                            ]));
    }
    public function dashboard_orden(Request $request)
    {
        $periodo=$request->periodo;
        session()->put('periodo', $periodo);
        $campo_universo='';
        $key_universo='';
        $titulo='';
        $origen='';
        $campos_group="";
        $nav_origen="PRINCIPAL";
        if(isset($request->tipo))
        {
            $nav_origen="DRILLDOWN";
            if($request->tipo=="E")
            {
                $campo_universo='empleado';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='E';
                
            }
            if($request->tipo=="G")
            {
                $campo_universo='udn';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='G';
                $campos_group='a.empleado,a.nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if($request->tipo=="R")
            {
                $campo_universo='region';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='R';
                $campos_group='a.udn,a.pdv';
                $campos_vista="udn as llave,pdv as value";
            }
        }
        else{
            if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
            {
                $campo_universo='empleado';
                $key_universo=Auth::user()->empleado;
                $titulo=Auth::user()->name;
                $origen='E';
            }
            if(Auth::user()->puesto=='Gerente')
            {
                $campo_universo='udn';
                $key_universo=Auth::user()->udn;
                $titulo=Auth::user()->pdv;
                $origen='G';
                $campos_group='a.empleado,a.nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if(Auth::user()->puesto=='Regional')
            {
                $campo_universo='region';
                $key_universo=Auth::user()->pdv;
                $titulo=Auth::user()->region;
                $origen='R';
                $campos_group='a.udn,a.pdv';
                $campos_vista="udn as llave,pdv as value";
            }
            if(Auth::user()->puesto=='Director')
            {
                $campo_universo=0;
                $key_universo=0;
                $titulo='Sucursales';
                $origen='D';
                $campos_group='a.region';
                $campos_vista="region as llave,region as value";
            }
            
        }
        $query_general=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_ac=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('producto','Activacion CON equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_as=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('producto','Activacion SIN equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_rc=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('producto','Renovacion CON equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_rs=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->whereRaw(''.$campo_universo.'=?',$key_universo)
            ->where('producto','Renovacion SIN equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();

            $details=0;
            if($origen=='G'||$origen=='R'||$origen=="D")
            {
                $sql_detalles="select ".$campos_vista.",aceptadas,facturadas,pendientes from (select ".$campos_group.",sum(aceptadas) as aceptadas,sum(facturadas) as facturadas,sum(pendientes) as pendientes from (";
                $sql_detalles=$sql_detalles."select ".$campos_group.",count(*) as aceptadas,0 as facturadas,0 as pendientes from ordenes as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and estatus_final like '%ACEPTADA%' group by ".$campos_group;
                $sql_detalles=$sql_detalles." UNION ";
                $sql_detalles=$sql_detalles."select ".$campos_group.",0 as aceptadas,count(*) as facturadas,0 as pendientes from ordenes as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and estatus_final like '%ACEPTADA - F%' group by ".$campos_group;
                $sql_detalles=$sql_detalles." UNION ";
                $sql_detalles=$sql_detalles."select ".$campos_group.",0 as aceptadas,0 as facturadas,count(*) as pendientes from ordenes as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and estatus_final like '%ACEPTADA - P%' group by ".$campos_group;
                $sql_detalles=$sql_detalles.") as a group by ".$campos_group.") as a order by a.pendientes desc";
                $details=DB::select(DB::raw(
                    $sql_detalles
                ));
            }
            return (view('dashboard_orden',['periodo'=>$periodo,
                                                'origen'=>$origen,
                                                'nav_origen'=>$nav_origen,
                                                'titulo'=>$titulo,
                                                'general'=>$query_general,
                                                'ac'=>$query_ac,
                                                'as'=>$query_as,
                                                'rc'=>$query_rc,
                                                'rs'=>$query_rs,
                                                'details'=>$details
          ]));
    }
    public function dashboard_productividad(Request $request)
    {   
        $periodo=$request->periodo;
        session()->put('periodo', $periodo);
        $campo_universo='';
        $key_universo='';
        $titulo='';
        $origen='';
        $campos_group="";
        $nav_origen="PRINCIPAL";
        if(isset($request->tipo))
        {
            $nav_origen="DRILLDOWN";
            if($request->tipo=="E")
            {
                $campo_universo='empleado';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='E';
                
            }
            if($request->tipo=="G")
            {
                $campo_universo='udn';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='G';
                $campos_group='empleado,nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if($request->tipo=="R")
            {
                $campo_universo='region';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='R';
                $campos_group='udn,pdv';
                $campos_vista="udn as llave,pdv as value";
            }
        }
        else{
            if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
            {
                $campo_universo='empleado';
                $key_universo=Auth::user()->empleado;
                $titulo=Auth::user()->name;
                $origen='E';
            }
            if(Auth::user()->puesto=='Gerente')
            {
                $campo_universo='udn';
                $key_universo=Auth::user()->udn;
                $titulo=Auth::user()->pdv;
                $origen='G';
                $campos_group='empleado,nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if(Auth::user()->puesto=='Regional')
            {
                $campo_universo='region';
                $key_universo=Auth::user()->pdv;
                $titulo=Auth::user()->region;
                $origen='R';
                $campos_group='udn,pdv';
                $campos_vista="udn as llave,pdv as value";
            }
            if(Auth::user()->puesto=='Director')
            {
                $campo_universo=0;
                $key_universo=0;
                $titulo='Sucursales';
                $origen='D';
                $campos_group='region';
                $campos_vista="region as llave,region as value";
            }
        }

        $hoy=substr(now(),0,10);

        $sql_tiempos="
            select dia,sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras,max(indice) as indice from (
                SELECT dia as dia,0 as interaccion,0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras ,'A' as indice FROM `estatico_dias` WHERE dia<='".$hoy."' and periodo='".$periodo."'
                UNION
                select lpad(created_at,10,0) as dia,sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'B' as indice  from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'C' as indice  from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'D' as indice  from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'E' as indice from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$hoy."' group by dia_trabajo
                UNION
                select dia_incidencia as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras,'F' as indice  from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' and dia_incidencia<='".$hoy."' group by dia_incidencia
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'G' as indice  from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras,'H' as indice  from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$hoy."' group by dia_trabajo
            )as a group by dia
        ";
        $query_tiempos=DB::select(DB::raw(
                $sql_tiempos
            ));
        if($origen=="E")
        {
            $reg_incidencias=Incidencia::whereRaw(''.$campo_universo.'=?',$key_universo)
                                        ->whereRaw("lpad(dia_incidencia,7,0)=?", [$periodo])
                                        ->get();
        }
        else
        {
            $reg_incidencias=0;
        }

        
        $ejecutivos=0;
        $min_objetivo=0;
        $ac=0;
        $as=0;
        $rc=0;
        $rs=0;
        if($origen=="E") 
        //SE UTILIZA EMERGENTE PARA LA AGRUPACION DE OBJETIVOS
        {
            $campo_universo='udn';
            $usuario_consultado=User::where('empleado',$key_universo)->get()->first();
            $key_universo=$usuario_consultado->udn;         
        }
        $query_objetivos=Objetivo::select(DB::raw($campo_universo.',ejecutivos,ac,asi,rc,rs,min_diario'))
                            ->where('periodo',$periodo)
                            ->whereRaw(''.$campo_universo.'=?',$key_universo)
                            ->get();
        //return($query_objetivos);

        foreach($query_objetivos as $objetivo)
        {
            $ejecutivos=$ejecutivos+$objetivo->ejecutivos;
            $min_objetivo=$min_objetivo+$objetivo->ejecutivos*$objetivo->min_diario;
            $ac=$ac+$objetivo->ac;
            $as=$as+$objetivo->asi;
            $rc=$rc+$objetivo->rc;
            $rs=$rs+$objetivo->rs;
        }

        try{
            if($origen=="E") 
            {
                $ac=$ac/$ejecutivos;
                $as=$as/$ejecutivos;
                $rc=$rc/$ejecutivos;
                $rs=$rs/$ejecutivos;
                $min_objetivo=$min_objetivo/$ejecutivos;
                $ejecutivos=1;
            }
        }
        catch(\Exception $e)
        {
            ;
        }
        /*
        $minutos_sucursal=420;
        if($origen=="E" || $origen=="G")
        {
            try{
            $sucursal_medida=Objetivo::where('udn',Auth::user()->udn)->where('periodo',$periodo)->dd()->first();
            $minutos_sucursal=$sucursal_medida->min_diario;
            }
            catch(\Exception $e)
            {
                $minutos_sucursal=420;
            }
        }*/
        $minutos_objetivo=$min_objetivo;
        $minutos_productivos_acum=0;
        $minutos_objetivo_acum=0;
        $minutos_incidencias_acum=0;
        $dias_transcurridos=0;
        $dias_incidencias=0;
        foreach($query_tiempos as $tiempo)
        {
            $minutos_objetivo_acum=$minutos_objetivo_acum+$minutos_objetivo;
            $dias_transcurridos=$dias_transcurridos+1;
            $dias_incidencias=$dias_incidencias+$tiempo->dias_incidencias;
            $minutos_productivos_acum=$minutos_productivos_acum+$tiempo->interaccion+$tiempo->funnel+$tiempo->ordenes+$tiempo->demanda+$tiempo->otras;
            $minutos_incidencias_acum=$minutos_incidencias_acum+$tiempo->incidencias;
        }
                          


        $details=0;
        $objetivos_detail="";
        if($origen=='G'||$origen=='R'||$origen=="D")
        {   
            $eje_fix="sum(ejecutivos)";
            if($origen=='G')
            {
                $eje_fix="1";
            }

            $sql_detalles="select llave,value,sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,".$eje_fix." as ejecutivos,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras from (
                select ".$campos_vista.",sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as ejecutivos,0 as incidencias,0 as dias_incidencias,0 as otras from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by ".$campos_group."
                UNION
                select ".$campos_vista.",0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as ejecutivos,0 as incidencias,0 as dias_incidencias,0 as otras from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by ".$campos_group."
                UNION
                select ".$campos_vista.",0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as ejecutivos,0 as incidencias,0 as dias_incidencias,0 as otras from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by ".$campos_group."
                UNION
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as ejecutivos,0 as incidencias,0 as dias_incidencias,0 as otras from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$hoy."' group by ".$campos_group."
                UNION
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as ejecutivos,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' and dia_incidencia<='".$hoy."' group by ".$campos_group."
                UNION
                select ".$campos_vista.",0 as interaccion,sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes, 0 as demanda, 0 as ejecutivos, 0 as incidencias, 0 as dias_incidencias,0 as otras from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by ".$campos_group." 
                UNION
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as ejecutivos,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$hoy."' group by ".$campos_group." 
                ";
            $sql_adicional_detalles="
                UNION 
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(ejecutivos) as ejecutivos,0 as incidencias,0 as dias_incidencias,0 as otras from objetivos where ".$campo_universo." = '".$key_universo."' and periodo ='".$periodo."' group by ".$campos_group."";
            if($origen=="R" || $origen=="D")
            {
                $sql_detalles=$sql_detalles."".$sql_adicional_detalles;
            }
            $sql_final=")as a group by a.llave,a.value";
            
            $sql_detalles=$sql_detalles."".$sql_final;
            $details=DB::select(DB::raw(
                $sql_detalles
            ));
            if($origen=="R")
            {
                $objetivos_detail=Objetivo::where('periodo',$periodo)->get();
            }
            if($origen=="D")
            {
                $regiones=Objetivo::select(DB::raw('distinct region as region'))
                                    ->where('periodo',$periodo)->get();
                $objetivos_detail=[];
                foreach($regiones as $region)
                {
                    $min_diario_region=0;
                    $objetivos_region=Objetivo::where('region',$region->region)->where('periodo',$periodo)->get();
                    foreach($objetivos_region as $objetivos)
                    {
                        $min_diario_region=$min_diario_region+$objetivos->min_diario*$objetivos->ejecutivos;
                    }
                    $objetivos_detail[]=['region'=>$region->region,'min_diario'=>$min_diario_region];
                }
                $objetivos_detail=collect($objetivos_detail);
            }
            if($origen=="G")
            {
                $ejecutivos=User::select('empleado')
                                    ->where('estatus','Activo')
                                    ->where('puesto','Ejecutivo')
                                    ->where('udn',$key_universo)->get();
                $objetivo=Objetivo::where('udn',$key_universo)->where('periodo',$periodo)->get()->first();
                $objetivos_detail=[];
                foreach($ejecutivos as $ejecutivo)
                {
                    try{
                        $min_diario_ejecutivo=$objetivo->min_diario;
                    }
                    catch(\Exception $e)
                    {
                        $min_diario_ejecutivo=0;
                    }
                    $objetivos_detail[]=['empleado'=>$ejecutivo->empleado,'min_diario'=>$min_diario_ejecutivo];
                }
                $objetivos_detail=collect($objetivos_detail);
            }
        }
        return (view('dashboard_productividad',['periodo'=>$periodo,
                                                'origen'=>$origen,
                                                'nav_origen'=>$nav_origen,
                                                'titulo'=>$titulo,
                                                'tiempos'=>$query_tiempos,
                                                'ac'=> $ac,
                                                'as'=> $as,
                                                'rc'=> $rc,
                                                'rs'=> $rs,
                                                'minutos_objetivo'=>$minutos_objetivo,
                                                'minutos_objetivo_acum'=>$minutos_objetivo_acum,
                                                'minutos_productivos_acum'=>$minutos_productivos_acum,
                                                'minutos_incidencias_acum'=>$minutos_incidencias_acum,
                                                'dias_transcurridos'=>$dias_transcurridos,
                                                'dias_incidencias'=>$dias_incidencias,
                                                'reg_incidencias'=>$reg_incidencias,
                                                'details'=>$details,
                                                'objetivos_detail'=>$objetivos_detail
          ]));
    }
    public function dashboard_resumen_periodo(Request $request)
    {
        $periodo=$request->periodo;
        session()->put('periodo', $periodo);
        $campo_universo='';
        $key_universo='';
        $titulo='';
        $origen='';
        $campos_group="";
        $nav_origen="PRINCIPAL";
        if(isset($request->tipo))
        {
            $nav_origen="DRILLDOWN";
            if($request->tipo=="E")
            {
                $campo_universo='empleado';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='E';
                
            }
            if($request->tipo=="G")
            {
                $campo_universo='udn';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='G';
                $campos_group='empleado,nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if($request->tipo=="R")
            {
                $campo_universo='region';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='R';
                $campos_group='udn,pdv';
                $campos_vista="udn as llave,pdv as value";
            }
        }
        else{
            if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
            {
                $campo_universo='empleado';
                $key_universo=Auth::user()->empleado;
                $titulo=Auth::user()->name;
                $origen='E';
            }
            if(Auth::user()->puesto=='Gerente')
            {
                $campo_universo='udn';
                $key_universo=Auth::user()->udn;
                $titulo=Auth::user()->pdv;
                $origen='G';
                $campos_group='empleado,nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if(Auth::user()->puesto=='Regional')
            {
                $campo_universo='region';
                $key_universo=Auth::user()->pdv;
                $titulo=Auth::user()->region;
                $origen='R';
                $campos_group='udn,pdv';
                $campos_vista="udn as llave,pdv as value";
            }
            if(Auth::user()->puesto=='Director')
            {
                $campo_universo=0;
                $key_universo=0;
                $titulo='Sucursales';
                $origen='D';
                $campos_group='region';
                $campos_vista="region as llave,region as value";
            }
        }
        $ac=0;
        $as=0;
        $rc=0;
        $rs=0;
        $min_diario=0;
        $ejecutivos=0;
        $min_diario_ejecutivos=0;
        $ac_q1=0;
        $as_q1=0;
        $rc_q1=0;
        $rs_q1=0;
        $ac_q2=0;
        $as_q2=0;
        $rc_q2=0;
        $rs_q2=0;

        $hoy=substr(now(),0,10);

        if($origen=="E")
        {
            $usuario=User::where('empleado',$key_universo)->get()->first();
            $objetivos=Objetivo::select(DB::raw('udn,sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,
                                                sum(ac_q1) as ac_q1,sum(as_q1) as as_q1,sum(rc_q1) as rc_q1,sum(rs_q1) as rs_q1,
                                                sum(ac_q2) as ac_q2,sum(as_q2) as as_q2,sum(rc_q2) as rc_q2,sum(rs_q2) as rs_q2,
                                                sum(min_diario) as min_diario,sum(ejecutivos) as ejecutivos'))
                            ->where('udn',$usuario->udn)
                            ->where('periodo',$periodo)
                            ->groupBy('udn')
                            ->get();
            foreach($objetivos as $objetivo)
            {
                $ejecutivos_sucursal=$objetivo->ejecutivos;
                $ac=$objetivo->ac/$ejecutivos_sucursal;
                $as=$objetivo->asi/$ejecutivos_sucursal;
                $rc=$objetivo->rc/$ejecutivos_sucursal;
                $rs=$objetivo->rs/$ejecutivos_sucursal;
                $ac_q1=$objetivo->ac_q1/$ejecutivos_sucursal;
                $as_q1=$objetivo->as_q1/$ejecutivos_sucursal;
                $rc_q1=$objetivo->rc_q1/$ejecutivos_sucursal;
                $rs_q1=$objetivo->rs_q1/$ejecutivos_sucursal;
                $ac_q2=$objetivo->ac_q2/$ejecutivos_sucursal;
                $as_q2=$objetivo->as_q2/$ejecutivos_sucursal;
                $rc_q2=$objetivo->rc_q2/$ejecutivos_sucursal;
                $rs_q2=$objetivo->rs_q2/$ejecutivos_sucursal;
                $min_diario=$objetivo->min_diario;
                $min_diario_ejecutivos=$objetivo->min_diario;
                $ejecutivos=1;
            }
        }
        else
        {
            $objetivos=Objetivo::whereRaw(''.$campo_universo.'=?',$key_universo)
                            ->where('periodo',$periodo)
                            ->get();

            foreach($objetivos as $objetivo)
            {
                $ac=$ac+$objetivo->ac;
                $as=$as+$objetivo->asi;
                $rc=$rc+$objetivo->rc;
                $rs=$rs+$objetivo->rs;
                $ac_q1=$ac_q1+$objetivo->ac_q1;
                $as_q1=$as_q2+$objetivo->as_q1;
                $rc_q1=$rc_q1+$objetivo->rc_q1;
                $rs_q1=$rs_q1+$objetivo->rs_q1;
                $ac_q2=$ac_q2+$objetivo->ac_q2;
                $as_q2=$as_q2+$objetivo->as_q2;
                $rc_q2=$rc_q2+$objetivo->rc_q2;
                $rs_q2=$rs_q2+$objetivo->rs_q2;
                $min_diario=$min_diario+$objetivo->min_diario;
                $ejecutivos=$ejecutivos+$objetivo->ejecutivos;
                $min_diario_ejecutivos=$min_diario_ejecutivos+($objetivo->min_diario*$objetivo->ejecutivos);
                
            }
        }
        
        //return($min_diario_ejecutivos);
        //return($objetivos);

        $avances=Ordenes::select(DB::raw('producto, count(*) as lineas,sum(renta) as rentas'))
                        ->whereRaw(''.$campo_universo.'=?',$key_universo)
                        ->whereRaw("lpad(created_at,7,0)=?",$periodo)
                        ->where('estatus_final','ACEPTADA - Facturada')
                        ->groupBy('producto')
                        ->get();

        $av_ac=$av_as=$av_rc=$av_rs=0;
        $rp_ac=$rp_as=$rp_rc=$rp_rs=0;
        $total_movimientos=0;
        foreach($avances as $avance)
        {
            $total_movimientos=$total_movimientos+$avance->lineas;
            if($avance->producto=='Activacion CON equipo'){$av_ac=$avance->lineas;$rp_ac=$avance->rentas/$avance->lineas;}
            if($avance->producto=='Activacion SIN equipo'){$av_as=$avance->lineas;$rp_as=$avance->rentas/$avance->lineas;}
            if($avance->producto=='Renovacion CON equipo'){$av_rc=$avance->lineas;$rp_rc=$avance->rentas/$avance->lineas;}
            if($avance->producto=='Renovacion SIN equipo'){$av_rs=$avance->lineas;$rp_rs=$avance->rentas/$avance->lineas;}
        }

        $avances=Ordenes::select(DB::raw('producto, count(*) as lineas,sum(renta) as rentas'))
                        ->whereRaw(''.$campo_universo.'=?',$key_universo)
                        ->whereRaw("lpad(created_at,7,0)=?",$periodo)
                        ->where('created_at','<=',$periodo.'-15')
                        ->where('estatus_final','ACEPTADA - Facturada')
                        ->groupBy('producto')
                        ->get();

        $av_ac_q1=$av_as_q1=$av_rc_q1=$av_rs_q1=0;
        $rp_ac_q1=$rp_as_q1=$rp_rc_q1=$rp_rs_q1=0;
        $total_movimientos_q1=0;
        foreach($avances as $avance)
        {
            $total_movimientos_q1=$total_movimientos_q1+$avance->lineas;
            if($avance->producto=='Activacion CON equipo'){$av_ac_q1=$avance->lineas;$rp_ac_q1=$avance->rentas/$avance->lineas;}
            if($avance->producto=='Activacion SIN equipo'){$av_as_q1=$avance->lineas;$rp_as_q1=$avance->rentas/$avance->lineas;}
            if($avance->producto=='Renovacion CON equipo'){$av_rc_q1=$avance->lineas;$rp_rc_q1=$avance->rentas/$avance->lineas;}
            if($avance->producto=='Renovacion SIN equipo'){$av_rs_q1=$avance->lineas;$rp_rs_q1=$avance->rentas/$avance->lineas;}
        }



        $dias_transcurridos=EstaticoDias::select(DB::raw('SUBSTRING(max(dia),9,2)*1 as transcurridos'))
                                            ->where('periodo',$periodo)
                                            ->whereRaw('dia<=now()')
                                            ->get()
                                            ->first();
        $dias_total=EstaticoDias::select(DB::raw('SUBSTRING(max(dia),9,2)*1 as total'))
                                            ->where('periodo',$periodo)
                                            ->get()
                                            ->first();


        $sql_tiempos="
        select lpad(dia,7,0) as periodo,sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras,max(indice) as indice from (
            SELECT dia as dia,0 as interaccion,0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'A' as indice FROM `estatico_dias` WHERE dia<=now() and periodo='".$periodo."'
            UNION
            select lpad(created_at,10,0) as dia,sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'B' as indice from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'C' as indice from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'D' as indice from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'E' as indice from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$hoy."' group by dia_trabajo
            UNION
            select dia_incidencia as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras,'F' as indice from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' and dia_incidencia<='".$hoy."' group by dia_incidencia
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'G' as indice from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras,'H' as indice from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$hoy."' group by dia_trabajo
        )as a group by lpad(dia,7,0)
        ";
        $query_tiempos=DB::select(DB::raw(
                $sql_tiempos
            ));

        $tiempo_productivo=0;
        $incidencias=0;
        foreach($query_tiempos as $tiempos)
        {
            $tiempo_productivo=$tiempo_productivo+$tiempos->interaccion;
            $tiempo_productivo=$tiempo_productivo+$tiempos->funnel;
            $tiempo_productivo=$tiempo_productivo+$tiempos->ordenes;
            $tiempo_productivo=$tiempo_productivo+$tiempos->demanda;
            $tiempo_productivo=$tiempo_productivo+$tiempos->otras;

            $incidencias=$incidencias+$tiempos->incidencias;
        }
        //return('Incidencias='.$incidencias);
        $minutos_objetivo=($min_diario_ejecutivos*$dias_transcurridos->transcurridos)-$incidencias;
        
        $p_productividad=$minutos_objetivo>0?100*$tiempo_productivo/$minutos_objetivo:0;

        $sql_tiempos_q1="
        select lpad(dia,7,0) as periodo,sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras,max(indice) as indice from (
            SELECT dia as dia,0 as interaccion,0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'A' as indice FROM `estatico_dias` WHERE dia<=now() and periodo='".$periodo."'
            UNION
            select lpad(created_at,10,0) as dia,sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'B' as indice from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'C' as indice from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'D' as indice from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'E' as indice from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$periodo."-15' and dia_trabajo<='".$hoy."' group by dia_trabajo
            UNION
            select dia_incidencia as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras,'F' as indice from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' and dia_incidencia<='".$periodo."-15' and dia_incidencia<='".$hoy."' group by dia_incidencia
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras,'G' as indice from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras,'H' as indice from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$periodo."-15' and dia_trabajo<='".$hoy."' group by dia_trabajo
        )as a group by lpad(dia,7,0)
        ";
        //return($sql_tiempos_q1);
        $query_tiempos_q1=DB::select(DB::raw(
                $sql_tiempos_q1
            ));

        $tiempo_productivo_q1=0;
        $incidencias_q1=0;
        foreach($query_tiempos_q1 as $tiempos)
        {
            $tiempo_productivo_q1=$tiempo_productivo_q1+$tiempos->interaccion;
            $tiempo_productivo_q1=$tiempo_productivo_q1+$tiempos->funnel;
            $tiempo_productivo_q1=$tiempo_productivo_q1+$tiempos->ordenes;
            $tiempo_productivo_q1=$tiempo_productivo_q1+$tiempos->demanda;
            $tiempo_productivo_q1=$tiempo_productivo_q1+$tiempos->otras;

            $incidencias_q1=$incidencias_q1+$tiempos->incidencias;
        }
        $minutos_objetivo_q1=$min_diario_ejecutivos*($dias_transcurridos->transcurridos>=15?15:$dias_transcurridos->transcurridos)-$incidencias_q1;
            
        $p_productividad_q1=$minutos_objetivo_q1>0?100*$tiempo_productivo_q1/$minutos_objetivo_q1:0;
        
        $minutos_objetivo_q2=$minutos_objetivo-$minutos_objetivo_q1;
        $tiempo_productivo_q2=$tiempo_productivo-$tiempo_productivo_q1;
        $p_productividad_q2=$minutos_objetivo_q2>0?100*$tiempo_productivo_q2/$minutos_objetivo_q2:0;
        
        $detalles=[];
        if($origen=="R")
        {
            $detalles=Ordenes::select(DB::raw('distinct udn as llave, pdv as value'))
                                ->where('region',$titulo)
                                ->where('pdv','<>',$titulo)
                                ->whereRaw('lpad(created_at,7,0)=?',$periodo)
                                ->get();
        }
        if($origen=="G")
        {
            $detalles=Ordenes::select(DB::raw('distinct empleado as llave, nombre as value'))
                                ->where('udn',$key_universo)
                                ->whereRaw('lpad(created_at,7,0)=?',$periodo)
                                ->get();
        }
        if($origen=="D")
        {
            $detalles=Ordenes::select(DB::raw('distinct region as llave, region as value'))
                                ->whereRaw('lpad(created_at,7,0)=?',$periodo)
                                ->get();
        }


        return view('dashboard_resumen_periodo',['periodo'=>$periodo,
                                                    'origen'=>$origen,
                                                    'nav_origen'=>$nav_origen,
                                                    'titulo'=>$titulo,
                                                    'ac'=>$ac,
                                                    'as'=>$as,
                                                    'rc'=>$rc,
                                                    'rs'=>$rs,
                                                    'ac_q1'=>$ac_q1,
                                                    'as_q1'=>$as_q1,
                                                    'rc_q1'=>$rc_q1,
                                                    'rs_q1'=>$rs_q1,
                                                    'ac_q2'=>$ac_q2,
                                                    'as_q2'=>$as_q2,
                                                    'rc_q2'=>$rc_q2,
                                                    'rs_q2'=>$rs_q2,
                                                    'av_ac'=>$av_ac,
                                                    'av_as'=>$av_as,
                                                    'av_rc'=>$av_rc,
                                                    'av_rs'=>$av_rs,
                                                    'rp_ac'=>$rp_ac,
                                                    'rp_as'=>$rp_as,
                                                    'rp_rc'=>$rp_rc,
                                                    'rp_rs'=>$rp_rs,
                                                    'av_ac_q1'=>$av_ac_q1,
                                                    'av_as_q1'=>$av_as_q1,
                                                    'av_rc_q1'=>$av_rc_q1,
                                                    'av_rs_q1'=>$av_rs_q1,
                                                    'rp_ac_q1'=>$rp_ac_q1,
                                                    'rp_as_q1'=>$rp_as_q1,
                                                    'rp_rc_q1'=>$rp_rc_q1,
                                                    'rp_rs_q1'=>$rp_rs_q1,
                                                    'transcurridos'=>$dias_transcurridos->transcurridos,
                                                    'total_dias'=>$dias_total->total,
                                                    'total_movimientos'=>$total_movimientos,
                                                    'total_movimientos_q1'=>$total_movimientos_q1,
                                                    'p_productividad'=>$p_productividad,
                                                    'p_productividad_q1'=>$p_productividad_q1,
                                                    'p_productividad_q2'=>$p_productividad_q2,
                                                    'minutos_objetivo'=>$minutos_objetivo,
                                                    'tiempo_productivo'=>$tiempo_productivo,
                                                    'minutos_objetivo_q1'=>$minutos_objetivo_q1,
                                                    'tiempo_productivo_q1'=>$tiempo_productivo_q1,
                                                    'minutos_objetivo_q2'=>$minutos_objetivo_q2,
                                                    'tiempo_productivo_q2'=>$tiempo_productivo_q2,
                                                    'detalles'=>$detalles
                                                ]);

    }
    public function dashboard_resumen_efectividad(Request $request)
    {
        $periodo=$request->periodo;
        session()->put('periodo', $periodo);
        $campo_universo='';
        $key_universo='';
        $titulo='';
        $origen='';
        $campos_group="";
        $nav_origen="PRINCIPAL";
        if(isset($request->tipo))
        {
            $nav_origen="DRILLDOWN";
            if($request->tipo=="E")
            {
                $campo_universo='empleado';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='E';
                
            }
            if($request->tipo=="G")
            {
                $campo_universo='udn';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='G';
                $campos_group='empleado,nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if($request->tipo=="R")
            {
                $campo_universo='region';
                $key_universo=$request->key;
                $titulo=$request->value;
                $origen='R';
                $campos_group='udn,pdv';
                $campos_vista="udn as llave,pdv as value";
            }
        }
        else{
            if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
            {
                $campo_universo='empleado';
                $key_universo=Auth::user()->empleado;
                $titulo=Auth::user()->name;
                $origen='E';
            }
            if(Auth::user()->puesto=='Gerente')
            {
                $campo_universo='udn';
                $key_universo=Auth::user()->udn;
                $titulo=Auth::user()->pdv;
                $origen='G';
                $campos_group='empleado,nombre';
                $campos_vista="empleado as llave,nombre as value";
            }
            if(Auth::user()->puesto=='Regional')
            {
                $campo_universo='region';
                $key_universo=Auth::user()->pdv;
                $titulo=Auth::user()->region;
                $origen='R';
                $campos_group='udn,pdv';
                $campos_vista="udn as llave,pdv as value";
            }
            if(Auth::user()->puesto=='Director')
            {
                $campo_universo=0;
                $key_universo=0;
                $titulo='Sucursales';
                $origen='D';
                $campos_group='region';
                $campos_vista="region as llave,region as value";
            }
        }

        $flujo=Interaccion::select(DB::raw('count(*) as flujo'))
                          ->whereRaw(''.$campo_universo.'=?',$key_universo)
                          ->whereRaw('lpad(created_at,7,0)=?',$periodo)
                          ->get()
                          ->first();
        $efectivas=Ordenes::select(DB::raw('origen,count(*) as efectivas'))
                          ->whereRaw(''.$campo_universo.'=?',$key_universo)
                          ->where('estatus_final','ACEPTADA - Facturada')
                          ->whereRaw('lpad(created_at,7,0)=?',$periodo)
                          ->groupBy('origen')
                          ->get();

        $demanda=GeneracionDemanda::select(DB::raw('sum(sms) as sms,sum(sms_individual) as sms_individual,sum(llamadas) as llamadas,sum(rs) as rs'))
                          ->whereRaw(''.$campo_universo.'=?',$key_universo)
                          ->whereRaw('lpad(dia_trabajo,7,0)=?',$periodo)
                          ->get()
                          ->first();

        $e_tienda=0;
        $e_llamada=0;
        $e_sms=0;
        $e_rs=0;
        foreach($efectivas as $origen_reg)
        {
            if($origen_reg->origen=='Llamada'){$e_llamada=$origen_reg->efectivas;}
            if($origen_reg->origen=='Redes Sociales'){$e_rs=$origen_reg->efectivas;}
            if($origen_reg->origen=='SMS'){$e_sms=$origen_reg->efectivas;}
            if($origen_reg->origen=='Tienda'){$e_tienda=$origen_reg->efectivas;}
        }

        $llamada=is_null($demanda->llamadas)?0:$demanda->llamadas;
        $sms=is_null($demanda->sms)?0:$demanda->sms;
        $sms_individual=is_null($demanda->sms_individual)?0:$demanda->sms_individual;
        $sms_total=$sms+$sms_individual;
        $rs=is_null($demanda->rs)?0:$demanda->rs;

        $detalles=[]; 
        if($origen=='G'||$origen=='R'||$origen=='D')
        {
            $sql_detalles="select ".$campos_vista.",flujo,facturadas_tda,demanda,facturadas_dem from (select ".$campos_group.",sum(flujo) as flujo,sum(facturadas_tda) as facturadas_tda,sum(demanda) as demanda,sum(facturadas_dem) as facturadas_dem from (";
            $sql_detalles=$sql_detalles."select ".$campos_group.",count(*) as flujo,0 as facturadas_tda,0 as demanda,0 as facturadas_dem from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."select ".$campos_group.",0 as flujo,count(*) as facturadas_tda,0 as demanda,0 as facturadas_dem from ordenes as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and estatus_final like '%ACEPTADA - F%' and origen='Tienda' group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."select ".$campos_group.",0 as flujo,0 as facturadas_tda,sum(llamadas+sms+sms_individual+rs) as demanda,0 as facturadas_dem from generacion_demandas as a where lpad(dia_trabajo,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."select ".$campos_group.",0 as flujo,0 as facturadas_tda,0 as demanda,count(*) as facturadas_dem from ordenes as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and estatus_final like '%ACEPTADA - F%' and origen!='Tienda' group by ".$campos_group;
            $sql_detalles=$sql_detalles.") as a group by ".$campos_group.") as a";
            //return($origen);
            $detalles=DB::select(DB::raw(
                $sql_detalles
            ));
        }
        
        return(view('dashboard_resumen_efectividad',['periodo'=>$periodo,
                                                     'origen'=>$origen,
                                                     'nav_origen'=>$nav_origen,
                                                     'titulo'=>$titulo,
                                                     'flujo'=>$flujo->flujo,
                                                     'e_tienda'=>$e_tienda,
                                                     'e_llamada'=>$e_llamada,
                                                     'e_sms'=>$e_sms,
                                                     'e_rs'=>$e_rs,
                                                     'llamada'=>$llamada,
                                                     'rs'=>$rs,
                                                     'sms'=>$sms_total,
                                                     'detalles'=>$detalles,
                                                    ]));
    }
    public function forma_carga_gastos(Request $request)
    {
        $periodos=RentabilidadPeriodosGastos::withCount('detalles')->orderBy('id','asc')->get();
        $ultimo_conocido=0;
        $periodo_carga_desc="";
        $periido_carga_id=0;
        foreach($periodos as $periodo)
        {
            if($periodo->detalles_count!=0)
            {
                $ultimo_conocido=$periodo->id;
            }
            else 
            {
                $periodo_carga_desc=$periodo->descripcion;
                $periodo_carga_id=$periodo->id;
                break;
            }
        }
        $ultimo_conocido_detalles=RentabilidadGastos::where('periodo',$ultimo_conocido);
        if(Auth::user()->puesto!="Director" && Auth::user()->puesto!="Regional")
        {
            $ultimo_conocido_detalles=$ultimo_conocido_detalles->where('udn',Auth::user()->udn);
        }
        $ultimo_conocido_detalles=$ultimo_conocido_detalles->get();

        return(view('rentabilidad_gastos',['ultimo_conocido_detalles'=>$ultimo_conocido_detalles,
                                           'periodo_carga_desc'=>$periodo_carga_desc,
                                           'periodo_carga_id'=>$periodo_carga_id
                                        ]));
    }
    public function dashboard_rentabilidad(Request $request)
    {
        $periodo=$request->periodo;//es el periodo del dashboard
        session()->put('periodo', $periodo);
        $nav_origen="PRINCIPAL";
        $sucursales=Sucursal::select('udn','pdv')->get();
        $sucursales=$sucursales->pluck('pdv','udn');
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
                $filtro=$request->value;
            }
            if($request->tipo=="R")
            {
                $origen='R';
                $filtro=$request->value;
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
            }
            if(Auth::user()->puesto=='Regional')
            {
                $origen='R';
                $filtro=Auth::user()->region;
            }
            if(Auth::user()->puesto=='Director')
            {
                $origen='D';
                $filtro='';
            }
        }
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
        $titulo_principal='';
        $gastos_fijos=0;
        $gastos_indirectos=0;
        $ingresos=0;
        $costos_venta=0;
        $gastos=RentabilidadGastos::where('periodo',$id_gastos)
                        ->select(DB::raw('sum(gastos_fijos) as g_f,sum(gastos_indirectos) as g_i'));
        $erp=ErpTransaccion::whereRaw('lpad(fecha,7,0)=?',$periodo)
                        ->select(DB::raw('sum(ingreso) as ingreso,sum(costo_venta) as c_v'))
                        ->where('direccion','SUCURSALES');
        $activaciones=[];
        $renovaciones=[];
        $aep=[];
        $rep=[];
        $add_ons=[];
        $seguros=[];
        $detalles=[];
        if($origen=="D")
        {
            $titulo_principal='Sucursales';
            $gastos=$gastos->get()->first();
            $erp=$erp->get()->first();   
            $activaciones=DB::select(DB::raw($this->getSQL($origen,'','TRADICIONAL','ACT',$periodo)));
            $renovaciones=DB::select(DB::raw($this->getSQL($origen,'','TRADICIONAL','REN',$periodo)));
            $aep=DB::select(DB::raw($this->getSQL($origen,'','TRADICIONAL','AEP',$periodo)));
            $rep=DB::select(DB::raw($this->getSQL($origen,'','TRADICIONAL','REP',$periodo)));
            $add_ons=DB::select(DB::raw($this->getSQL($origen,'','ADDON','ADD',$periodo)));
            $seguros=DB::select(DB::raw($this->getSQL($origen,'','SEGUROS','SEG',$periodo)));
            $detalles=DB::select(DB::raw($this->getSQL_detalles($origen,'',$id_gastos,$periodo)));
        }
        if($origen=="R")
        {
            $titulo_principal='REGION '.$filtro;
            $gastos=$gastos->where('region',$filtro)->get()->first();
            $erp=$erp->where('region',$filtro)->get()->first(); 
            $activaciones=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','ACT',$periodo)));
            $renovaciones=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','REN',$periodo)));
            $aep=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','AEP',$periodo)));
            $rep=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','REP',$periodo)));
            $add_ons=DB::select(DB::raw($this->getSQL($origen,$filtro,'ADDON','ADD',$periodo)));
            $seguros=DB::select(DB::raw($this->getSQL($origen,$filtro,'SEGUROS','SEG',$periodo)));           
            $detalles=DB::select(DB::raw($this->getSQL_detalles($origen,$filtro,$id_gastos,$periodo)));
        }
        if($origen=="G" || $origen=="E")
        {
            $titulo_principal=''.$sucursales[$filtro];
            $gastos=$gastos->where('udn',$filtro)->get()->first();
            $erp=$erp->where('udn',$filtro)->get()->first();   
            $activaciones=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','ACT',$periodo)));
            $renovaciones=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','REN',$periodo)));
            $aep=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','AEP',$periodo)));
            $rep=DB::select(DB::raw($this->getSQL($origen,$filtro,'TRADICIONAL','REP',$periodo)));
            $add_ons=DB::select(DB::raw($this->getSQL($origen,$filtro,'ADDON','ADD',$periodo)));
            $seguros=DB::select(DB::raw($this->getSQL($origen,$filtro,'SEGUROS','SEG',$periodo)));
            $detalles=DB::select(DB::raw($this->getSQL_detalles($origen,$filtro,$id_gastos,$periodo)));
        }
        $ingresos=$erp->ingreso;
        $costos_venta=$erp->c_v;
        $gastos_fijos=$gastos->g_f;
        $gastos_indirectos=$gastos->g_i;
        $sum_gastos=$costos_venta+$gastos_fijos+$gastos_indirectos;
        $porc_rentabilidad=$sum_gastos>0?100*$ingresos/$sum_gastos:0;

        /*
        $sql_sucursales="
            select distinct udn from (
                SELECT distinct udn FROM erp_transaccions WHERE lpad(fecha,7,0)='".$periodo."'
                UNION
                SELECT udn FROM rentabilidad_gastos WHERE periodo=".$id_gastos."
                ) as a
                        ";
        $sucursales=collect(DB::select(DB::raw($sql_sucursales)))->pluck('udn');

        return($sucursales);
                */
        $brackets=DB::select(DB::raw('select * from brackets where tipo="TRADICIONAL"'));
        
        return(view('dashboard_rentabilidad',[
                                                'nav_origen'=>$nav_origen,
                                                'origen'=>$origen,
                                                'periodo'=>$periodo,
                                                'consistencia'=>$consistencia,
                                                'titulo_principal'=>$titulo_principal,
                                                'gastos_fijos'=>$gastos_fijos,
                                                'gastos_indirectos'=>$gastos_indirectos,
                                                'ingresos'=>$ingresos,
                                                'costos_venta'=>$costos_venta,
                                                'titulo_gastos'=>$titulo_gastos,
                                                'sum_gastos'=>$sum_gastos,
                                                'porc_rentabilidad'=>$porc_rentabilidad,
                                                'activaciones'=>$activaciones,
                                                'renovaciones'=>$renovaciones,
                                                'aep'=>$aep,
                                                'rep'=>$rep,
                                                'add_ons'=>$add_ons,
                                                'seguros'=>$seguros,
                                                'bracket_desde'=>collect($brackets)->pluck('desde','bracket'),
                                                'bracket_hasta'=>collect($brackets)->pluck('hasta','bracket'),
                                                'detalles'=>$detalles,
                                                'sucursales'=>$sucursales
                                            ]));
    }
    private function getSQL($origen,$filtro,$tipo,$tipo_consulta,$periodo)
    {
        $filtro_adicional='';
        if($origen=="R")
        {
            $filtro_adicional=" and region='".$filtro."'";
        }
        if($origen=="E" || $origen=="G")
        {
            $filtro_adicional=" and udn='".$filtro."'";
        }
        $sql="select bracket, sum(n) as n,sum(ingreso) as ingreso,sum(c_v) as c_v from ( 
            select bracket,0 as n,0 as ingreso,0 as c_v from brackets where tipo='".$tipo."'
            UNION
            select bracket,count(*),sum(ingreso) as ingreso,sum(costo_venta) c_v 
            from erp_transaccions
            where 
            lpad(fecha,7,0)='".$periodo."' and 
            tipo_estandar='".$tipo_consulta."' ".$filtro_adicional." and
            direccion='SUCURSALES'
            group by bracket) as a 
            group by bracket";
        return($sql);
    }
    private function getSQL_detalles($origen,$filtro,$periodo_gastos,$periodo_transacciones)
    {
        $filtro_adicional='';
        $llave='';
        if($origen=="D")
        {
            $llave='region';
        }
        if($origen=="R")
        {
            $llave='udn';
            $filtro_adicional=" and region='".$filtro."'";
        }
        if($origen=="E" || $origen=="G")
        {
            $llave='udn';
            $filtro_adicional=" and udn='".$filtro."'";
        }
        $sql="
            select * from (
            select ".$llave." as llave,sum(gastos_fijos) as gastos_fijos,sum(gastos_indirectos) as gastos_indirectos,sum(ingresos) as ingresos, sum(c_v) as c_v,(100*sum(ingresos)/sum(gastos_fijos+gastos_indirectos+c_v)) as rentabilidad from(
            select ".$llave.",gastos_fijos,gastos_indirectos,0 as ingresos, 0 as c_v from rentabilidad_gastos where periodo=".$periodo_gastos."".$filtro_adicional."  
            UNION
            select ".$llave." as llave,0 as gastos_fijos,0 as gastos_indirectos,sum(ingreso) as ingresos, sum(costo_venta) as c_v from erp_transaccions where lpad(fecha,7,0)='".$periodo_transacciones."'".$filtro_adicional." and direccion='SUCURSALES' group by udn,region
                ) as a group by a.".$llave."
                )as a order by rentabilidad desc;
             ";
        return($sql);
    }
    public function export_rentabilidad(Request $request)
    {
        $periodo=$request->periodo;
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
        
        $gastos_fijos=0;
        $gastos_indirectos=0;
        $ingresos=0;
        $costos_venta=0;
        $gastos=RentabilidadGastos::where('periodo',$id_gastos)
                        ->select(DB::raw('sum(gastos_fijos) as g_f,sum(gastos_indirectos) as g_i'));
        $erp=ErpTransaccion::whereRaw('lpad(fecha,7,0)=?',$periodo)
                        ->select(DB::raw('sum(ingreso) as ingreso,sum(costo_venta) as c_v'))
                        ->where('direccion','SUCURSALES');
        $gastos=$gastos->get()->first();
        $erp=$erp->get()->first(); 
        
        $ingresos=$erp->ingreso;
        $costos_venta=$erp->c_v;
        $gastos_fijos=$gastos->g_f;
        $gastos_indirectos=$gastos->g_i;
        $sum_gastos=$costos_venta+$gastos_fijos+$gastos_indirectos;
        $porc_rentabilidad=$sum_gastos>0?100*$ingresos/$sum_gastos:0;



        $detalles_regiones=DB::select(DB::raw($this->getSQL_detalles('D','',$id_gastos,$periodo)));
        $detalles_centro=DB::select(DB::raw($this->getSQL_detalles('R','CENTRO',$id_gastos,$periodo)));
        $detalles_norte=DB::select(DB::raw($this->getSQL_detalles('R','NORTE',$id_gastos,$periodo)));
        $detalles_sur=DB::select(DB::raw($this->getSQL_detalles('R','SUR',$id_gastos,$periodo)));

        $sucursales=Sucursal::all()->pluck('pdv','udn');
    
        return(view('export_rentabilidad',[
                                            'ingresos'=>$ingresos,
                                            'costos_venta'=>$costos_venta,
                                            'gastos_fijos'=>$gastos_fijos,
                                            'gastos_indirectos'=>$gastos_indirectos,
                                            'gastos'=>$sum_gastos,
                                            'porc_rentabilidad'=>$porc_rentabilidad,
                                            'detalles_regiones'=>$detalles_regiones,
                                            'detalles_centro'=>$detalles_centro,
                                            'detalles_norte'=>$detalles_norte,
                                            'detalles_sur'=>$detalles_sur,
                                            'sucursales'=>$sucursales
                                        ]));
        return($detalles_sur);
    }
}
