<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Interaccion;
use App\Models\Ordenes;

class ExportController extends Controller
{
    public function export_interaccion(Request $request)
    {
    
        $periodo=$request->periodo;
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
        $query_resultados=Interaccion::where($campo_universo,$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get();
        return (view('export_interaccion',['resultados'=>$query_resultados]));    
    }
    public function export_orden(Request $request)
    {
    
        $periodo=$request->periodo;
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
        $query_resultados=Ordenes::where($campo_universo,$key_universo)
            ->whereRaw("lpad(created_at,7,0) =?", [$periodo])
            ->get();
        return (view('export_orden',['resultados'=>$query_resultados]));    
    }
}
