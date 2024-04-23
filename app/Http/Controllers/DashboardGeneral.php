<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ErpTransaccion;

class DashboardGeneral extends Controller
{
    public function general(Request $request)
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


        $sql_año_anterior_suc_mas="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
        
        $sql_año_actual_suc_mas="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
        
        $sql_año_anterior_suc_neg="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
                   
        $sql_año_actual_suc_neg="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SUCURSALES' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
                    
        $datos_año_anterior_suc_mas=DB::select(DB::raw($sql_año_anterior_suc_mas));
        $datos_año_anterior_suc_mas=collect($datos_año_anterior_suc_mas);

        $datos_año_suc_mas=DB::select(DB::raw($sql_año_actual_suc_mas));
        $datos_año_suc_mas=collect($datos_año_suc_mas);

        $datos_año_anterior_suc_neg=DB::select(DB::raw($sql_año_anterior_suc_neg));
        $datos_año_anterior_suc_neg=collect($datos_año_anterior_suc_neg);

        $datos_año_suc_neg=DB::select(DB::raw($sql_año_actual_suc_neg));
        $datos_año_suc_neg=collect($datos_año_suc_neg);

        //SOCIOS COMERCIALES

        $sql_año_anterior_gab_mas="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
                    
        $sql_año_actual_gab_mas="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año."' and (servicio not like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
                    
        $sql_año_anterior_gab_neg="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año_anterior."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
                               
        $sql_año_actual_gab_neg="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año."' and (servicio like '%neg%') and direccion='SOCIOS' ".$filtro."group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";

                    return($sql_año_actual_gab_neg);  

        $datos_año_anterior_gab_mas=DB::select(DB::raw($sql_año_anterior_gab_mas));
        $datos_año_anterior_gab_mas=collect($datos_año_anterior_gab_mas);

        $datos_año_gab_mas=DB::select(DB::raw($sql_año_actual_gab_mas));
        $datos_año_gab_mas=collect($datos_año_gab_mas);

        $datos_año_anterior_gab_neg=DB::select(DB::raw($sql_año_anterior_gab_neg));
        $datos_año_anterior_gab_neg=collect($datos_año_anterior_gab_neg);

        $datos_año_gab_neg=DB::select(DB::raw($sql_año_actual_gab_neg));
        $datos_año_gab_neg=collect($datos_año_gab_neg);

        //PREPARA DATOS EN ARREGLOS PARA VISTA

        //SUCURSALES

        $año_anterior_suc_mas_array=[];

        foreach($datos_año_anterior_suc_mas as $mes_anterior)
        {
            $año_anterior_suc_mas_array[]=[
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
        $año_suc_mas_array=[];
        foreach($datos_año_suc_mas as $mes)
        {
            $año_suc_mas_array[]=[
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

        $año_anterior_suc_neg_array=[];

        foreach($datos_año_anterior_suc_neg as $mes_anterior)
        {
            $año_anterior_suc_neg_array[]=[
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
        $año_suc_neg_array=[];
        foreach($datos_año_suc_neg as $mes)
        {
            $año_suc_neg_array[]=[
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

        //SOCIOS

        $año_anterior_gab_mas_array=[];

        foreach($datos_año_anterior_gab_mas as $mes_anterior)
        {
            $año_anterior_gab_mas_array[]=[
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
        $año_gab_mas_array=[];
        foreach($datos_año_gab_mas as $mes)
        {
            $año_gab_mas_array[]=[
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

        $año_anterior_gab_neg_array=[];

        foreach($datos_año_anterior_gab_neg as $mes_anterior)
        {
            $año_anterior_gab_neg_array[]=[
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
        $año_gab_neg_array=[];
        foreach($datos_año_gab_neg as $mes)
        {
            $año_gab_neg_array[]=[
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

        $ultimo_dia=ErpTransaccion::select(DB::raw('max(fecha) as ultimo'))
                                    ->get()
                                    ->first()
                                    ->ultimo;


        //FIX ARREGLO SUCURSALES
        $nuevo_año_anterior_suc_neg=[];
        
        foreach($año_anterior_suc_mas_array as $instancia)  //se usa masivo de sucursales como referencia de año anterior dado que es seguro que los periodos hasta la actualidad esten poblados
        {
            $encontrado=0;
            foreach($año_anterior_suc_neg_array as $instancia2)
            {
                if($instancia2['periodo']==$instancia['periodo'])
                {
                    $encontrado=1;
                    $nuevo_año_anterior_suc_neg[]=[
                                                'periodo'=>$instancia2['periodo'],
                                                'act'=>$instancia2['act'],
                                                'monto_act'=>$instancia2['monto_act'],
                                                'ren'=>$instancia2['ren'],
                                                'monto_ren'=>$instancia2['monto_ren'],
                                                'aep'=>$instancia2['aep'],
                                                'monto_aep'=>$instancia2['monto_aep'],
                                                'rep'=>$instancia2['rep'],
                                                'monto_rep'=>$instancia2['monto_rep']
                                                ];   
                }
            }
            if($encontrado==0)
            {
                $nuevo_año_anterior_suc_neg[]=[
                                            'periodo'=>$instancia['periodo'],
                                            'act'=>0,
                                            'monto_act'=>0,
                                            'ren'=>0,
                                            'monto_ren'=>0,
                                            'aep'=>0,
                                            'monto_aep'=>0,
                                            'rep'=>0,
                                            'monto_rep'=>0
                                            ];   
            }
        }
        $año_anterior_suc_neg_array=$nuevo_año_anterior_suc_neg;

        $nuevo_año_suc_neg=[];
        
        foreach($año_suc_mas_array as $instancia)  //se usa masivo de sucursales como referencia de año anterior dado que es seguro que los periodos hasta la actualidad esten poblados
        {
            $encontrado=0;
            foreach($año_suc_neg_array as $instancia2)
            {
                if($instancia2['periodo']==$instancia['periodo'])
                {
                    $encontrado=1;
                    $nuevo_año_suc_neg[]=[
                                                'periodo'=>$instancia2['periodo'],
                                                'act'=>$instancia2['act'],
                                                'monto_act'=>$instancia2['monto_act'],
                                                'ren'=>$instancia2['ren'],
                                                'monto_ren'=>$instancia2['monto_ren'],
                                                'aep'=>$instancia2['aep'],
                                                'monto_aep'=>$instancia2['monto_aep'],
                                                'rep'=>$instancia2['rep'],
                                                'monto_rep'=>$instancia2['monto_rep']
                                                ];   
                }
            }
            if($encontrado==0)
            {
                $nuevo_año_suc_neg[]=[
                                            'periodo'=>$instancia['periodo'],
                                            'act'=>0,
                                            'monto_act'=>0,
                                            'ren'=>0,
                                            'monto_ren'=>0,
                                            'aep'=>0,
                                            'monto_aep'=>0,
                                            'rep'=>0,
                                            'monto_rep'=>0
                                            ];   
            }
        }
        $año_suc_neg_array=$nuevo_año_suc_neg;


        $nuevo_año_gab_mas=[];
        
        foreach($año_suc_mas_array as $instancia)  //se usa masivo de sucursales como referencia de año anterior dado que es seguro que los periodos hasta la actualidad esten poblados
        {
            $encontrado=0;
            foreach($año_gab_mas_array as $instancia2)
            {
                if($instancia2['periodo']==$instancia['periodo'])
                {
                    $encontrado=1;
                    $nuevo_año_gab_mas[]=[
                                                'periodo'=>$instancia2['periodo'],
                                                'act'=>$instancia2['act'],
                                                'monto_act'=>$instancia2['monto_act'],
                                                'ren'=>$instancia2['ren'],
                                                'monto_ren'=>$instancia2['monto_ren'],
                                                'aep'=>$instancia2['aep'],
                                                'monto_aep'=>$instancia2['monto_aep'],
                                                'rep'=>$instancia2['rep'],
                                                'monto_rep'=>$instancia2['monto_rep']
                                                ];   
                }
            }
            if($encontrado==0)
            {
                $nuevo_año_gab_mas[]=[
                                            'periodo'=>$instancia['periodo'],
                                            'act'=>0,
                                            'monto_act'=>0,
                                            'ren'=>0,
                                            'monto_ren'=>0,
                                            'aep'=>0,
                                            'monto_aep'=>0,
                                            'rep'=>0,
                                            'monto_rep'=>0
                                            ];   
            }
        }
        $año_gab_mas_array=$nuevo_año_gab_mas;

        $nuevo_año_gab_neg=[];
        
        foreach($año_suc_mas_array as $instancia)  //se usa masivo de sucursales como referencia de año anterior dado que es seguro que los periodos hasta la actualidad esten poblados
        {
            $encontrado=0;
            foreach($año_gab_neg_array as $instancia2)
            {
                if($instancia2['periodo']==$instancia['periodo'])
                {
                    $encontrado=1;
                    $nuevo_año_gab_neg[]=[
                                                'periodo'=>$instancia2['periodo'],
                                                'act'=>$instancia2['act'],
                                                'monto_act'=>$instancia2['monto_act'],
                                                'ren'=>$instancia2['ren'],
                                                'monto_ren'=>$instancia2['monto_ren'],
                                                'aep'=>$instancia2['aep'],
                                                'monto_aep'=>$instancia2['monto_aep'],
                                                'rep'=>$instancia2['rep'],
                                                'monto_rep'=>$instancia2['monto_rep']
                                                ];   
                }
            }
            if($encontrado==0)
            {
                $nuevo_año_gab_neg[]=[
                                            'periodo'=>$instancia['periodo'],
                                            'act'=>0,
                                            'monto_act'=>0,
                                            'ren'=>0,
                                            'monto_ren'=>0,
                                            'aep'=>0,
                                            'monto_aep'=>0,
                                            'rep'=>0,
                                            'monto_rep'=>0
                                            ];   
            }
        }
        $año_gab_neg_array=$nuevo_año_gab_neg;


        return(view('dashboard_dg',['periodo'=>$periodo,
                            'nav_origen'=>$nav_origen,
                            'origen'=>$origen,
                            'titulo'=>$titulo,
                            'meses'=>$meses,
                            'año'=>$año,
                            'año_anterior'=>$año_anterior,
                            'ultimo_dia'=>$ultimo_dia,
                            'año_anterior_gab_neg_array'=>$año_anterior_gab_neg_array,
                            'año_anterior_gab_mas_array'=>$año_anterior_gab_mas_array,
                            'año_gab_neg_array'=>$año_gab_neg_array,
                            'año_gab_mas_array'=>$año_gab_mas_array,
                            'año_anterior_suc_neg_array'=>$año_anterior_suc_neg_array,
                            'año_anterior_suc_mas_array'=>$año_anterior_suc_mas_array,
                            'año_suc_neg_array'=>$año_suc_neg_array,
                            'año_suc_mas_array'=>$año_suc_mas_array,
                        ]));
    }
}
