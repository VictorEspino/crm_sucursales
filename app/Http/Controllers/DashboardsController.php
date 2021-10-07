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

class DashboardsController extends Controller
{
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
        $ordenes=Ordenes::select(DB::raw('count(*) as facturadas'))
                ->where($campo_universo,$key_universo)
                ->where('estatus_final','ACEPTADA - Facturada')
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();

        if($tipo=="1")
        {
            $visitas=Interaccion::select(DB::raw('count(*) as visitas'))
                ->where($campo_universo,$key_universo)
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();
            if($visitas->visitas!="0")
            {
                return(number_format(100*$ordenes->facturadas/$visitas->visitas,0));
            }
            else
            {
                return(0);
            }
        }
        if($tipo=="2")
        {
            $ordenes_total=Ordenes::select(DB::raw('count(*) as total'))
                ->where($campo_universo,$key_universo)
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();
            if($ordenes_total->total!="0")
            {
                return(number_format(100*$ordenes->facturadas/$ordenes_total->total,0));
            }
            else
            {
                return(0);
            }
        }
        if($tipo=="3")
        {
            $visitas=Interaccion::select(DB::raw('count(*) as visitas'))
                ->where($campo_universo,$key_universo)
                ->where('intencion',1)
                ->whereRaw("lpad(created_at,7,0) = lpad(now(),7,0)")
                ->get()
                ->first();
            if($visitas->visitas!="0")
            {
                return(number_format(100*$ordenes->facturadas/$visitas->visitas,0));
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
            "select distinct periodo from estatico_dias where dia<=now() order by dia desc"
        ));
        $periodos=collect($periodos)->take(6);
        return(view('modificar_objetivo',['periodos'=>$periodos]));
    }
    public function dashboard_central()
    {
        $periodos=DB::select(DB::raw(
            "select distinct periodo from estatico_dias where dia<=now() order by dia desc"
        ));
        $periodos=collect($periodos)->take(6);
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
        }
        
        $query_visitas=Interaccion::select(DB::raw("count(*) as visitas"))
            ->where($campo_universo,$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_intencion=Interaccion::select(DB::raw("count(*) as visitas"))
            ->where($campo_universo,$key_universo)
            ->where('intencion',1)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_solicitudes=Interaccion::select(DB::raw("count(*) as visitas"))
            ->where($campo_universo,$key_universo)
            ->where('fin_interaccion','Orden')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_aprobadas=Ordenes::select(DB::raw("count(*) as ordenes"))
            ->where($campo_universo,$key_universo)
            ->where('estatus_final','like','ACEPTADA%')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_facturadas=Ordenes::select(DB::raw("count(*) as ordenes"))
            ->where($campo_universo,$key_universo)
            ->where('estatus_final','like','ACEPTADA - F%')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get()
            ->first();
        $query_productos=Ordenes::select(DB::raw('producto,count(*) as ordenes'))
            ->where($campo_universo,$key_universo)
            ->where('estatus_final','like','ACEPTADA - F%')
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
        $query_cuotas=Objetivo::select(DB::raw($campo_universo.",sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,sum(ejecutivos) as ejecutivos"))
            ->where($campo_universo,$key_universo)
            ->where('periodo',$periodo)
            ->groupBy($campo_universo)
            ->get()
            ->first();

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
            $a_ac=100*$ac/$c_ac;
            $a_as=100*$as/$c_as;
            $a_rc=100*$rc/$c_rc;
            $a_rs=100*$rs/$c_rs;
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
        if($origen=='G'||$origen=='R')
        {
            $sql_detalles="select ".$campos_vista.",visitas,intencion,facturadas from (select ".$campos_group.",sum(visitas) as visitas,sum(intencion) as intencion,sum(facturadas) as facturadas from (";
            $sql_detalles=$sql_detalles."select ".$campos_group.",count(*) as visitas,0 as intencion,0 as facturadas  from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."'  group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."select ".$campos_group.",0 as visitas,count(*) as intencion,0 as facturadas  from interaccions as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and intencion=1 group by ".$campos_group;
            $sql_detalles=$sql_detalles." UNION ";
            $sql_detalles=$sql_detalles."SELECT ".$campos_group.",0 as visitas,0 as intencion, count(*) as facturadas from ordenes as a where lpad(created_at,7,0)='".$periodo."' and ".$campo_universo."='".$key_universo."' and estatus_final='ACEPTADA - Facturada' group by ".$campos_group;
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
        }

        $ci=0;
        $si=0;
        $pi=0;
        $ps=0;
        $total=0;
        $query_intencion=Interaccion::select(DB::raw("intencion,count(*) as visitas"))
            ->where($campo_universo,$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('intencion')
            ->get();
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
            ->where($campo_universo,$key_universo)
            ->where('intencion','1')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('tramite')
            ->get();
        $query_fin_c=Interaccion::select(DB::raw("fin_interaccion,count(*) as visitas"))
            ->where($campo_universo,$key_universo)
            ->where('intencion','1')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('fin_interaccion')
            ->get();
        $query_tramite_s=Interaccion::select(DB::raw("tramite,count(*) as visitas"))
            ->where($campo_universo,$key_universo)
            ->where('intencion','0')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('tramite')
            ->get();
        $query_fin_s=Interaccion::select(DB::raw("fin_interaccion,count(*) as visitas"))
            ->where($campo_universo,$key_universo)
            ->where('intencion','0')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('fin_interaccion')
            ->get();

        //OBTIENE TABLA DE DETALLES
        $details=0;
        if($origen=='G'||$origen=='R')
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
                                              'details'=>$details
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
        }
        $query_general=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->where($campo_universo,$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_ac=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->where($campo_universo,$key_universo)
            ->where('producto','Activacion CON equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_as=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->where($campo_universo,$key_universo)
            ->where('producto','Activacion SIN equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_rc=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->where($campo_universo,$key_universo)
            ->where('producto','Renovacion CON equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();
        $query_rs=Ordenes::select(DB::raw("estatus_final,count(*) as ordenes"))
            ->where($campo_universo,$key_universo)
            ->where('producto','Renovacion SIN equipo')
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->groupBy('estatus_final')
            ->get();

            $details=0;
            if($origen=='G'||$origen=='R')
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
        }
        $sql_tiempos="
            select dia,sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras from (
                SELECT dia as dia,0 as interaccion,0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras FROM `estatico_dias` WHERE dia<=now() and periodo='".$periodo."'
                UNION
                select lpad(created_at,10,0) as dia,sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' group by dia_trabajo
                UNION
                select dia_incidencia as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' group by dia_incidencia
                UNION
                select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
                UNION
                select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' group by dia_trabajo
            )as a group by dia
        ";
        //return($sql_tiempos);
        $query_tiempos=DB::select(DB::raw(
                $sql_tiempos
            ));
        if($origen=="E")
        {
            $reg_incidencias=Incidencia::where($campo_universo,$key_universo)
                                        ->whereRaw("lpad(dia_incidencia,7,0)=?", [$periodo])
                                        ->get();
        }
        else
        {
            $reg_incidencias=0;
        }

        
        $ejecutivos=0;
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
        $query_objetivos=Objetivo::select(DB::raw($campo_universo.',sum(ejecutivos) as ejecutivos,sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs'))
                            ->where('periodo',$periodo)
                            ->where($campo_universo,$key_universo)
                            ->groupBy($campo_universo)
                            ->get()
                            ->first();
        //return($query_objetivos);
        try{
            if($origen=="E") 
            {
                $ejecutivos=1;
                $ac=$query_objetivos->ac/$ejecutivos;
                $as=$query_objetivos->asi/$ejecutivos;
                $rc=$query_objetivos->rc/$ejecutivos;
                $rs=$query_objetivos->rs/$ejecutivos;
            }
            else
            {
                $ejecutivos=$query_objetivos->ejecutivos;
                $ac=$query_objetivos->ac;
                $as=$query_objetivos->asi;
                $rc=$query_objetivos->rc;
                $rs=$query_objetivos->rs;
            }
        }
        catch(\Exception $e)
        {
            ;
        }
        $minutos_sucursal=360;
        if($origen=="E" || $origen=="G")
        {
            try{
            $sucursal_medida=Objetivo::where('udn',Auth::user()->udn)->where('periodo',$periodo)->get()->first();
            $minutos_sucursal=$sucursal_medida->min_diario;
            }
            catch(\Exception $e)
            {
                $minutos_sucursal=360;
            }
        }
        $minutos_objetivo=$minutos_sucursal*$ejecutivos;
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
        if($origen=='G'||$origen=='R')
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
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as ejecutivos,0 as incidencias,0 as dias_incidencias,0 as otras from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' group by ".$campos_group."
                UNION
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as ejecutivos,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' group by ".$campos_group."
                UNION
                select ".$campos_vista.",0 as interaccion,sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes, 0 as demanda, 0 as ejecutivos, 0 as incidencias, 0 as dias_incidencias,0 as otras from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by ".$campos_group." 
                UNION
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as ejecutivos,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by ".$campos_group." 
                ";
            $sql_adicional_detalles="
                UNION 
                select ".$campos_vista.",0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(ejecutivos) as ejecutivos,0 as incidencias,0 as dias_incidencias,0 as otras from objetivos where ".$campo_universo." = '".$key_universo."' and periodo ='".$periodo."' group by ".$campos_group."";
            if($origen=="R")
            {
                $sql_detalles=$sql_detalles."".$sql_adicional_detalles;
            }
            $sql_final=")as a group by a.llave,a.value";
            $sql_detalles=$sql_detalles."".$sql_final;
            //return($sql_detalles);
            $details=DB::select(DB::raw(
                $sql_detalles
            ));
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
                                                'minutos_sucursal'=>$minutos_sucursal,
                                                'minutos_objetivo'=>$minutos_objetivo,
                                                'minutos_objetivo_acum'=>$minutos_objetivo_acum,
                                                'minutos_productivos_acum'=>$minutos_productivos_acum,
                                                'minutos_incidencias_acum'=>$minutos_incidencias_acum,
                                                'dias_transcurridos'=>$dias_transcurridos,
                                                'dias_incidencias'=>$dias_incidencias,
                                                'reg_incidencias'=>$reg_incidencias,
                                                'details'=>$details
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
        }
        $ac=0;
        $as=0;
        $rc=0;
        $rs=0;
        $min_diario=0;
        $ejecutivos=0;
        if($origen=="E")
        {
            $usuario=User::where('empleado',$key_universo)->get()->first();
            $objetivos=Objetivo::select(DB::raw('udn,sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,sum(min_diario) as min_diario,sum(ejecutivos) as ejecutivos'))
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
                $min_diario=$objetivo->min_diario;
                $ejecutivos=1;
            }
        }
        else
        {
            $objetivos=Objetivo::select(DB::raw($campo_universo.',sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,sum(min_diario) as min_diario,sum(ejecutivos) as ejecutivos'))
                            ->where($campo_universo,$key_universo)
                            ->where('periodo',$periodo)
                            ->groupBy($campo_universo)
                            ->get();

            foreach($objetivos as $objetivo)
            {
                $ac=$objetivo->ac;
                $as=$objetivo->asi;
                $rc=$objetivo->rc;
                $rs=$objetivo->rs;
                $min_diario=$objetivo->min_diario;
                $ejecutivos=$objetivo->ejecutivos;
            }
        }
        

        //return($objetivos);

        $avances=Ordenes::select(DB::raw('producto, count(*) as lineas,sum(renta) as rentas'))
                        ->where($campo_universo,$key_universo)
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
                        ->where($campo_universo,$key_universo)
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
        select lpad(dia,7,0) as periodo,sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras from (
            SELECT dia as dia,0 as interaccion,0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras FROM `estatico_dias` WHERE dia<=now() and periodo='".$periodo."'
            UNION
            select lpad(created_at,10,0) as dia,sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' group by dia_trabajo
            UNION
            select dia_incidencia as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' group by dia_incidencia
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' group by dia_trabajo
        )as a group by lpad(dia,7,0)
        ";
        //return($sql_tiempos);
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
        //return('min_diario='.$min_diario.', dias_transcurridos='.$dias_transcurridos->transcurridos.', Incidencias='.$incidencias);
        $minutos_objetivo=$ejecutivos*$min_diario*$dias_transcurridos->transcurridos-$incidencias;
        
        $p_productividad=$minutos_objetivo>0?100*$tiempo_productivo/$minutos_objetivo:0;

        $sql_tiempos_q1="
        select lpad(dia,7,0) as periodo,sum(interaccion) as interaccion,sum(funnel) as funnel,sum(ordenes) as ordenes,sum(demanda) as demanda,sum(incidencias) as incidencias,sum(dias_incidencias) as dias_incidencias,sum(otras) as otras from (
            SELECT dia as dia,0 as interaccion,0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras FROM `estatico_dias` WHERE dia<=now() and periodo='".$periodo."'
            UNION
            select lpad(created_at,10,0) as dia,sum(minutos) as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from interaccions where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos) as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from funnels where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, 0 as funnel,sum(minutos) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from ordenes where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,sum(minutos) as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from generacion_demandas where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$periodo."-15' group by dia_trabajo
            UNION
            select dia_incidencia as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,sum(minutos) as incidencias,count(*) as dias_incidencias,0 as otras from incidencias where ".$campo_universo." = '".$key_universo."' and lpad(dia_incidencia,7,0) ='".$periodo."' and dia_incidencia<='".$periodo."-15' group by dia_incidencia
            UNION
            select lpad(created_at,10,0) as dia,0 as interaccion, sum(minutos_funnel) as funnel,sum(minutos_orden) as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,0 as otras from time_updates where ".$campo_universo." = '".$key_universo."' and lpad(created_at,7,0) ='".$periodo."' and created_at<='".$periodo."-15' group by lpad(created_at,10,0)
            UNION
            select dia_trabajo as dia,0 as interaccion, 0 as funnel,0 as ordenes,0 as demanda,0 as incidencias,0 as dias_incidencias,sum(minutos) as otras from actividades_extras where ".$campo_universo." = '".$key_universo."' and lpad(dia_trabajo,7,0) ='".$periodo."' and dia_trabajo<='".$periodo."-15' group by dia_trabajo
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
        $minutos_objetivo_q1=$ejecutivos*$min_diario*($dias_transcurridos->transcurridos>=15?15:$dias_transcurridos->transcurridos)-$incidencias_q1;
            
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


        return view('dashboard_resumen_periodo',['periodo'=>$periodo,
                                                    'origen'=>$origen,
                                                    'nav_origen'=>$nav_origen,
                                                    'titulo'=>$titulo,
                                                    'ac'=>$ac,
                                                    'as'=>$as,
                                                    'rc'=>$rc,
                                                    'rs'=>$rs,
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
}
