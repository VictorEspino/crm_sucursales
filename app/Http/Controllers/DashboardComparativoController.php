<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ErpTransaccion;
use App\Models\RentabilidadPeriodosGastos;
use App\Models\RentabilidadGastos;

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

            if($request->tipo=="G")
            {
                $campo_universo='udn';
                $key_universo=$request->key;
                $filtro=" and ".$campo_universo."='".$key_universo."' ";
                $titulo=$request->value;
                $origen='G';
            }
            if($request->tipo=="R")
            {
                $campo_universo='region';
                $key_universo=$request->key;
                $filtro=" and ".$campo_universo."='".$key_universo."' ";
                $titulo=$request->value;
                $origen='R';
            }
        }
        else{
            if(Auth::user()->puesto=='Gerente')
            {
                $campo_universo='udn';
                $key_universo=Auth::user()->udn;
                $filtro=" and ".$campo_universo."='".$key_universo."' ";
                $titulo=Auth::user()->pdv;
                $origen='G';
            }
            if(Auth::user()->puesto=='Regional')
            {
                $campo_universo='region';
                $key_universo=Auth::user()->pdv;
                $filtro=" and ".$campo_universo."='".$key_universo."' ";
                $titulo=Auth::user()->region;
                $origen='R';
            }
            if(Auth::user()->puesto=='Director')
            {
                $campo_universo=0;
                $key_universo=0;
                $filtro="";
                $titulo='Sucursales';
                $origen='D';
            }
        }
        $año=substr($periodo,0,4);
        $año_anterior=$año-1;
        $lista_dd=[];
        $sql_año_anterior="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
        
        $sql_año_actual="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año."' and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";

        $sql_año_anterior_flujo="SELECT lpad(created_at,7,0) as periodo,count(*) as flujo FROM interaccions where lpad(created_at,4,0)=".$año_anterior." ".$filtro."group by lpad(created_at,7,0)";
        $sql_año_actual_flujo="SELECT lpad(created_at,7,0) as periodo,count(*) as flujo FROM interaccions where lpad(created_at,4,0)=".$año." ".$filtro."group by lpad(created_at,7,0)";
        $flujo_año_anterior=[
                        ['periodo'=>$año_anterior.'-01','flujo'=>0],
                        ['periodo'=>$año_anterior.'-02','flujo'=>0],
                        ['periodo'=>$año_anterior.'-03','flujo'=>0],
                        ['periodo'=>$año_anterior.'-04','flujo'=>0],
                        ['periodo'=>$año_anterior.'-05','flujo'=>0],
                        ['periodo'=>$año_anterior.'-06','flujo'=>0],
                        ['periodo'=>$año_anterior.'-07','flujo'=>0],
                        ['periodo'=>$año_anterior.'-08','flujo'=>0],
                        ['periodo'=>$año_anterior.'-09','flujo'=>0],
                        ['periodo'=>$año_anterior.'-10','flujo'=>0],
                        ['periodo'=>$año_anterior.'-11','flujo'=>0],
                        ['periodo'=>$año_anterior.'-12','flujo'=>0],
                        ];
        $flujo_año_actual=[];

        $datos_año_anterior=DB::select(DB::raw($sql_año_anterior));
        $datos_año_anterior=collect($datos_año_anterior);

        $datos_año=DB::select(DB::raw($sql_año_actual));
        $datos_año=collect($datos_año);

        if($año_anterior>2021)
        {
            $flujo_año_anterior=[];
            $flujo_anterior=DB::select(DB::raw($sql_año_anterior_flujo));
            foreach($flujo_anterior as $periodo_flujo)
            {
                $flujo_año_anterior[]=['periodo'=>$periodo_flujo->periodo,'flujo'=>$periodo_flujo->flujo];
            }
        }


        $flujo_actual=DB::select(DB::raw($sql_año_actual_flujo));
        foreach($flujo_actual as $periodo_flujo)
        {
            $flujo_año_actual[]=['periodo'=>$periodo_flujo->periodo,'flujo'=>$periodo_flujo->flujo];
        }

        $año_anterior_array=[];
        foreach($datos_año_anterior as $mes_anterior)
        {
            $año_anterior_array[]=[
                                    'periodo'=>$mes_anterior->periodo,
                                    'act'=>$mes_anterior->act,
                                    'monto_act'=>$mes_anterior->monto_act,
                                    'ren'=>$mes_anterior->ren,
                                    'monto_ren'=>$mes_anterior->monto_ren,
                                    'aep'=>$mes_anterior->aep,
                                    'monto_aep'=>$mes_anterior->monto_aep,
                                    'rep'=>$mes_anterior->rep,
                                    'monto_rep'=>$mes_anterior->monto_rep,
                                  ];
        }
        $año_array=[];
        foreach($datos_año as $mes)
        {
            $año_array[]=[
                                    'periodo'=>$mes->periodo,
                                    'act'=>$mes->act,
                                    'monto_act'=>$mes->monto_act,
                                    'ren'=>$mes->ren,
                                    'monto_ren'=>$mes->monto_ren,
                                    'aep'=>$mes->aep,
                                    'monto_aep'=>$mes->monto_aep,
                                    'rep'=>$mes->rep,
                                    'monto_rep'=>$mes->monto_rep,
                                  ];
        }
        $meses=[];
        $meses['01']='Enero';
        $meses['02']='Febrero';
        $meses['03']='Marzo';
        $meses['04']='Abril';
        $meses['05']='Mayo';
        $meses['06']='Junio';
        $meses['07']='Julio';
        $meses['08']='Agosto';
        $meses['09']='Septiembre';
        $meses['10']='Octubre';
        $meses['11']='Noviembre';
        $meses['12']='Diciembre';

        if($origen=="R")
        {
            $lista_dd=ErpTransaccion::select(DB::raw('distinct udn as llave, pdv as value'))
                                ->where('region',$titulo)
                                ->where('direccion','SUCURSALES')
                                ->whereRaw('lpad(fecha,7,0)=?',$periodo)
                                ->get();
        }
        if($origen=="D")
        {
            $lista_dd=ErpTransaccion::select(DB::raw('distinct region as llave, region as value'))
                                ->whereRaw('lpad(fecha,7,0)=?',$periodo)
                                ->where('direccion','SUCURSALES')
                                ->get();
        }
        //RENTABILIDAD
        $rentabilidad_año_anterior=[
            ['periodo'=>$año_anterior.'-01','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-02','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-03','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-04','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-05','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-06','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-07','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-08','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-09','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-10','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-11','rentabilidad'=>0],
            ['periodo'=>$año_anterior.'-12','rentabilidad'=>0],
            ];
        $rentabilidad_año_actual=[];
        if($año_anterior>2021)
        {
            foreach($rentabilidad_año_anterior as $index=>$periodo_rentabilidad)
            {
                $rent_periodo=$this->getRentabilidad($origen,$periodo_rentabilidad['periodo'],$key_universo);
                $rentabilidad_año_anterior[$index]['rentabilidad']=$rent_periodo['rentabilidad'];
            }
        }
        

        foreach($datos_año as $periodo_año_actual) //CORRE SOBRE DATOS AÑO PARA SOLO OBTENER DONDE HAY TRANSACCIONES
        {
            $rent_periodo=$this->getRentabilidad($origen,$periodo_año_actual->periodo,$key_universo);
            $rentabilidad_periodo_actual=$rent_periodo['rentabilidad'];
            $rentabilidad_año_actual[]=['periodo'=>$periodo_año_actual->periodo,'rentabilidad'=>$rentabilidad_periodo_actual];
        }


        return(view('dashboard_comparativo',['periodo'=>$periodo,
                                            'nav_origen'=>$nav_origen,
                                            'origen'=>$origen,
                                            'titulo'=>$titulo,
                                            'meses'=>$meses,
                                            'año'=>$año,
                                            'año_anterior'=>$año_anterior,
                                            'datos_año_anterior'=>$datos_año_anterior,
                                            'datos_año'=>$datos_año,
                                            'datos_año_anterior_array'=>$año_anterior_array,
                                            'datos_año_array'=>$año_array,
                                            'flujo_año_anterior'=>$flujo_año_anterior,
                                            'flujo_año_actual'=>$flujo_año_actual,
                                            'lista_dd'=>$lista_dd,
                                            'rentabilidad_año_anterior'=>$rentabilidad_año_anterior,
                                            'rentabilidad_año_actual'=>$rentabilidad_año_actual
                                        ]));
    }
    private function getRentabilidad($origen,$periodo,$filtro)
    {
        $respuesta=[
                    'periodo'=>$periodo,
                    'rentabilidad'=>0,
                    'consistente'=>'NO'
                    ];
        $consistencia=false;
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
        if($origen=='D')
        {
            $gastos=$gastos->get()->first();
            $erp=$erp->get()->first();  
        }
        if($origen=='R')
        {
            $gastos=$gastos->where('region',$filtro)->get()->first();
            $erp=$erp->where('region',$filtro)->get()->first(); 
        }
        if($origen=='G')
        {
            $gastos=$gastos->where('udn',$filtro)->get()->first();
            $erp=$erp->where('udn',$filtro)->get()->first();   
        }
        $ingresos=$erp->ingreso;
        $costos_venta=$erp->c_v;
        $gastos_fijos=$gastos->g_f;
        $gastos_indirectos=$gastos->g_i;
        $sum_gastos=$costos_venta+$gastos_fijos+$gastos_indirectos;
        $porc_rentabilidad=$sum_gastos>0?100*$ingresos/$sum_gastos:0;
        $respuesta['rentabilidad']=$porc_rentabilidad;
        $respuesta['consistente']=$consistencia?'SI':'NO';
        return($respuesta);
    }
}
