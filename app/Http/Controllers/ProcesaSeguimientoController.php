<?php

namespace App\Http\Controllers;
use App\Models\Funnel;
use App\Models\Ordenes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ProcesaSeguimientoController extends Controller
{
    public function seguimiento_funnel(Request $request)
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
            $registros=Funnel::where($campo_universo,$key_universo)
                                ->where('cliente','like','%'.$_GET["query"].'%')
                                ->where('estatus','!=','Orden')
                                ->orderBy('created_at','desc')
                                ->paginate(10);
            $registros->appends($request->all());
            return(view('seguimiento_funnel',['registros'=>$registros,'query'=>$_GET['query']]));
        }
        else
        {
            $registros=Funnel::where($campo_universo,$key_universo)
                                ->where('estatus','!=','Orden')
                                ->orderBy('created_at','desc')                                
                                ->paginate(10);
            return(view('seguimiento_funnel',['registros'=>$registros,'query'=>'']));
        }
    }
    public function seguimiento_funnel_calendario(Request $request)
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

        $registros=Funnel::where($campo_universo,$key_universo)
                            ->select('id',DB::raw('cliente as title, fecha_sig_contacto as start'))
                            ->where('estatus','!=','Orden')
                            ->orderBy('created_at','desc')
                            ->get();                      
                            //return($registros);         
        $SQL_inicio="select lpad(now(),10,0) as hoy from dual";
        $inicio=DB::select(DB::raw(
            $SQL_inicio
        ));

        return(view('seguimiento_funnel_calendario',['registros'=>$registros,'inicio'=>$inicio[0]->hoy]));
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
}