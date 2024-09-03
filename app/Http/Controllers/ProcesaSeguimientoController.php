<?php

namespace App\Http\Controllers;
use App\Models\Funnel;
use App\Models\Ordenes;
use App\Models\Incidencia;
use App\Models\Objetivo;
use App\Models\Sucursal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ActividadesExtra;

use Illuminate\Http\Request;

class ProcesaSeguimientoController extends Controller
{
    public function seguimiento_funnel(Request $request)
    {
        $campo_universo='';
        $key_universo='';
    
        if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
        }
        if(Auth::user()->puesto=='Gerente')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
        }
        if(Auth::user()->puesto=='Regional')
        {
            $campo_universo='region';
            $key_universo=Auth::user()->pdv;
        }


        if(isset($_GET['query']))
        {
            $registros=Funnel::where($campo_universo,$key_universo)
                                ->where('cliente','like','%'.$_GET["query"].'%')
                                ->where('estatus','!=','Orden')
                                ->where('estatus','!=','Finalizar Seguimiento')
                                ->orderBy('created_at','desc')
                                ->paginate(10);
            $registros->appends($request->all());
            return(view('seguimiento_funnel',['registros'=>$registros,'query'=>$_GET['query']]));
        }
        else
        {
            $registros=Funnel::where($campo_universo,$key_universo)
                                ->where('estatus','!=','Orden')
                                ->where('estatus','!=','Finalizar Seguimiento')
                                ->orderBy('created_at','desc')                                
                                ->paginate(10);
            return(view('seguimiento_funnel',['registros'=>$registros,'query'=>'']));
        }
    }
    public function seguimiento_funnel_calendario(Request $request)
    {
        $campo_universo='';
        $key_universo='';

        $filtro_opcion=[];
    
        if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
        }
        if(Auth::user()->puesto=='Gerente')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
        }
        if(Auth::user()->puesto=='Regional')
        {
            $campo_universo='region';
            $key_universo=Auth::user()->pdv;
        }

        $filtro_opcion=Sucursal::where($campo_universo,$key_universo)
                                ->where('estatus','Activo')
                                ->orderBy('pdv','asc')
                                ->get();

        $sucursal=0;

        if(isset($_GET['query']))
        {
            $sucursal=$_GET["query"];
        }

        $registros=Funnel::where($campo_universo,$key_universo)
                            ->select('id',DB::raw('cliente as title, fecha_sig_contacto as start,CASE WHEN fecha_sig_contacto<date(now()) THEN "#FF0000" ELSE "#0073e6" END as backgroundColor'))
                            ->where('estatus','!=','Orden')
                            ->where('estatus','!=','Finalizar Seguimiento')
                            ->when($sucursal != 0, function ($query) use ($sucursal) {
                                return $query->where('udn', $sucursal);
                            })
                            ->orderBy('created_at','desc')
                            ->get();                      

        $SQL_inicio="select lpad(now(),10,0) as hoy from dual";
        $inicio=DB::select(DB::raw(
            $SQL_inicio
        ));

        return(view('seguimiento_funnel_calendario',['registros'=>$registros,'inicio'=>$inicio[0]->hoy,'filtro_opcion'=>$filtro_opcion,'filtro'=>$sucursal]));
    }
    public function seguimiento_orden(Request $request)
    {
        $campo_universo='';
        $key_universo='';
    
        if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
        {
            $campo_universo='empleado';
            $key_universo=Auth::user()->empleado;
        }
        if(Auth::user()->puesto=='Gerente')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
        }
        if(Auth::user()->puesto=='Regional')
        {
            $campo_universo='region';
            $key_universo=Auth::user()->pdv;
        }


        if(isset($_GET['query']))
        {
            $registros=Ordenes::where($campo_universo,$key_universo)
                                ->where(function($query) {
                                    $query->where('cliente','like','%'.$_GET["query"].'%')
                                          ->orWhere('numero_orden','like','%'.$_GET["query"].'%');
                                })
                                ->orderBy('created_at','desc')
                                ->paginate(10);
            $registros->appends($request->all());
            return(view('seguimiento_orden',['registros'=>$registros,'query'=>$_GET['query']]));
        }
        else
        {
            $registros=Ordenes::where($campo_universo,$key_universo)
                                ->orderBy('created_at','desc')
                                ->paginate(10);
            return(view('seguimiento_orden',['registros'=>$registros,'query'=>'']));
        }
    }
    public function seguimiento_incidencias(Request $request)
    {
        $campo_universo='';
        $key_universo='';
    
        if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
        {
            $campo_universo='empleado';
            $key_universo=Auth::user()->empleado;
        }
        if(Auth::user()->puesto=='Gerente')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
        }
        if(Auth::user()->puesto=='Regional')
        {
            $campo_universo='region';
            $key_universo=Auth::user()->pdv;
        }


        if(isset($_GET['query']))
        {
            $registros=Incidencia::where($campo_universo,$key_universo)
                                ->where('dia_incidencia',$_GET["query"])
                                ->orderBy('dia_incidencia','desc')
                                ->paginate(10);
            $registros->appends($request->all());
            return(view('seguimiento_incidencias',['registros'=>$registros,'query'=>$_GET['query']]));
        }
        else
        {
            $registros=Incidencia::where($campo_universo,$key_universo)
                                ->orderBy('dia_incidencia','desc')
                                ->paginate(10);
            return(view('seguimiento_incidencias',['registros'=>$registros,'query'=>'']));
        }
    }
    public function objetivos_review(Request $request)
    {
        $campo_filtro='0';
        $valor_filtro='0';
        if(Auth::user()->puesto=='Regional')
        {
            $campo_filtro='region';
            $valor_filtro=Auth::user()->region;
        }
        $sql_registros="
        select * from (
            select a.udn,a.pdv,a.region,sum(ac) as ac,sum(asi) as asi,sum(rc) as rc,sum(rs) as rs,sum(min_diario) as min_diario,sum(ejecutivos) as ejecutivos from (
            SELECT udn,pdv,region,0 as ac,0 as asi,0 as rc,0 as rs,0 as min_diario,0 as ejecutivos FROM sucursals where estatus='Activo' and ".$campo_filtro."='".$valor_filtro."'
            UNION
            SELECT udn,pdv,region,ac,asi,rc,rs,min_diario,ejecutivos from objetivos where periodo='".substr(now(),0,7)."' and ".$campo_filtro."='".$valor_filtro."'
                ) as a group by a.udn,a.pdv,a.region) as b order by b.region asc,b.pdv asc
        ";
        $registros=DB::select(DB::raw($sql_registros));
        return(view('objetivos_review',['registros'=>$registros]));
    }

    public function seguimiento_actividades(Request $request)
    {
        $campo_universo='';
        $key_universo='';
    
        if(Auth::user()->puesto=='Ejecutivo' || Auth::user()->puesto=='Otro')
        {
            $campo_universo='empleado';
            $key_universo=Auth::user()->empleado;
        }
        if(Auth::user()->puesto=='Gerente')
        {
            $campo_universo='udn';
            $key_universo=Auth::user()->udn;
        }
        if(Auth::user()->puesto=='Regional')
        {
            $campo_universo='region';
            $key_universo=Auth::user()->pdv;
        }


        if(isset($_GET['query']))
        {
            $registros=ActividadesExtra::where($campo_universo,$key_universo)
                                ->where('dia_trabajo',$_GET["query"])
                                ->orderBy('dia_trabajo','desc')
                                ->paginate(10);
            $registros->appends($request->all());
            return(view('seguimiento_actividades',['registros'=>$registros,'query'=>$_GET['query']]));
        }
        else
        {
            $registros=ActividadesExtra::where($campo_universo,$key_universo)
                                ->orderBy('dia_trabajo','desc')
                                ->paginate(10);
            return(view('seguimiento_actividades',['registros'=>$registros,'query'=>'']));
        }
    }
}
