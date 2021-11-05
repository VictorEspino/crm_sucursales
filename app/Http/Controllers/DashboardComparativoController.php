<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardComparativoController extends Controller
{
    public function dashboard_comparativo(Request $request)
    {
        $periodo=$request->periodo;//es el periodo del dashboard
        session()->put('periodo', $periodo);
        $nav_origen="PRINCIPAL";
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
        return(view('dashboard_comparativo',['periodo'=>$periodo,
                                             'nav_origen'=>$nav_origen,
                                             'origen'=>$origen,
                                             'titulo'=>$titulo
                                            ]));
    }
}
