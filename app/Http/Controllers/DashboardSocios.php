<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EstaticoDias;

class DashboardSocios extends Controller
{
    public function diario(Request $request)
    {
        $periodo=$request->periodo;
        session()->put('periodo', $periodo);
        $sql_indicadores="
                select 'MASIVO' as tipo,tipo_estandar,count(*) as lineas, sum(importe) as monto from erp_transaccions where lpad(fecha,7,0)='".$request->periodo."' and servicio not like '%NEG%' and direccion='SOCIOS' group by tipo_estandar
                UNION
                select 'EMPRESARIAL' as tipo,tipo_estandar,count(*) as lineas, sum(importe) as monto from erp_transaccions where lpad(fecha,7,0)='".$request->periodo."' and servicio  like '%NEG%' and direccion='SOCIOS' group by tipo_estandar
                        ";
        $consulta=DB::select(DB::raw($sql_indicadores));
        $consulta=collect($consulta);
        $act_masivo_l=0;
        $ren_masivo_l=0;
        $aep_masivo_l=0;
        $rep_masivo_l=0;
        $act_masivo_m=0;
        $ren_masivo_m=0;
        $aep_masivo_m=0;
        $rep_masivo_m=0;
        $act_empresarial_l=0;
        $ren_empresarial_l=0;
        $act_empresarial_m=0;
        $ren_empresarial_m=0;
        foreach($consulta as $registro)
        {
            if($registro->tipo=='MASIVO')
            {
                if($registro->tipo_estandar=='ACT')
                {
                    $act_masivo_l=$act_masivo_l+$registro->lineas;
                    $act_masivo_m=$act_masivo_m+$registro->monto;
                }
                if($registro->tipo_estandar=='REN')
                {
                    $ren_masivo_l=$ren_masivo_l+$registro->lineas;
                    $ren_masivo_m=$ren_masivo_m+$registro->monto;
                }
                if($registro->tipo_estandar=='AEP')
                {
                    $aep_masivo_l=$aep_masivo_l+$registro->lineas;
                    $aep_masivo_m=$aep_masivo_m+$registro->monto;
                }
                if($registro->tipo_estandar=='REP')
                {
                    $rep_masivo_l=$rep_masivo_l+$registro->lineas;
                    $rep_masivo_m=$rep_masivo_m+$registro->monto;
                }
            }
            if($registro->tipo=='EMPRESARIAL')
            {
                if($registro->tipo_estandar=='ACT')
                {
                    $act_empresarial_l=$act_empresarial_l+$registro->lineas;
                    $act_empresarial_m=$act_empresarial_m+$registro->monto;
                }
                if($registro->tipo_estandar=='REN')
                {
                    $ren_empresarial_l=$ren_empresarial_l+$registro->lineas;
                    $ren_empresarial_m=$ren_empresarial_m+$registro->monto;
                }
            }
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

        return view('dashboard_socios_diario',[
                                                'periodo'=>$periodo,
                                                'transcurridos'=>$dias_transcurridos->transcurridos,
                                                'dias_total'=>$dias_total->total,
                                                'act_masivo_l'=>$act_masivo_l,
                                                'ren_masivo_l'=>$ren_masivo_l,
                                                'aep_masivo_l'=>$aep_masivo_l,
                                                'rep_masivo_l'=>$rep_masivo_l,
                                                'act_masivo_m'=>$act_masivo_m,
                                                'ren_masivo_m'=>$ren_masivo_m,
                                                'aep_masivo_m'=>$aep_masivo_m,
                                                'rep_masivo_m'=>$rep_masivo_m,
                                                'act_empresarial_l'=>$act_empresarial_l,
                                                'ren_empresarial_l'=>$ren_empresarial_l,
                                                'act_empresarial_m'=>$act_empresarial_m,
                                                'ren_empresarial_m'=>$ren_empresarial_m,

                                            ]);
    }
    public function comparativo(Request $request)
    {
        $periodo=$request->periodo;
        $año=substr($periodo,0,4);
        $año_anterior=$año-1;

        $sql_año_anterior="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";

        $sql_año_actual="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep,sum(ren) as ren,sum(monto_ren) as monto_ren,sum(rep) as rep,sum(monto_rep) as monto_rep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren,0 as aep,0 as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,count(*) as aep,sum(importe) as monto_aep, 0 as rep,0 as monto_rep FROM `erp_transaccions` where tipo_estandar='AEP' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'rep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,0 as ren,0 as monto_ren,0 as aep,0 as monto_aep, count(*) as rep,sum(importe) as monto_rep FROM `erp_transaccions` where tipo_estandar='REP' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";

        $sql_ventas_avs_anterior="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as aep,0 as monto_aep FROM `erp_transaccions` where tipo='Activación' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as aep,sum(importe) as monto_aep FROM `erp_transaccions` where tipo='Activación Equipo Propio' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)                    
                    ) as a group by a.periodo
                    ";

        $sql_ventas_avs_actual="
                    select periodo,sum(act) as act,sum(monto_act) as monto_act,sum(aep) as aep,sum(monto_aep) as monto_aep from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as aep,0 as monto_aep FROM `erp_transaccions` where tipo='Activación' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'aep' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as aep,sum(importe) as monto_aep FROM `erp_transaccions` where tipo='Activación Equipo Propio' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' and pdv not like '%VENTAS EMPRESARIAL CORPORATIVO%' group by lpad(fecha,7,0)                    
                    ) as a group by a.periodo
                    ";

        $sql_cont_ambas_anterior="
                    select periodo,sum(monto_act+monto_ren) as contribucion from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año_anterior."' and direccion='SOCIOS' group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";
        
        $sql_cont_ambas="
                    select periodo,sum(monto_act+monto_ren) as contribucion from
                    (
                    SELECT 'act' as mov,lpad(fecha,7,0) as periodo,count(*) as act, sum(importe) as monto_act,0 as ren,0 as monto_ren FROM `erp_transaccions` where tipo_estandar='ACT' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' group by lpad(fecha,7,0)
                    UNION
                    SELECT 'ren' as mov,lpad(fecha,7,0) as periodo,0 as act, 0 as monto_act,count(*) as ren,sum(importe) as monto_ren FROM `erp_transaccions` where tipo_estandar='REN' and lpad(fecha,4,0)='".$año."' and direccion='SOCIOS' group by lpad(fecha,7,0)
                    ) as a group by a.periodo
                    ";

        $datos_año_anterior=DB::select(DB::raw($sql_año_anterior));
        $datos_año_anterior=collect($datos_año_anterior);

        $datos_año=DB::select(DB::raw($sql_año_actual));
        $datos_año=collect($datos_año);

        $ventas_avs_anterior=DB::select(DB::raw($sql_ventas_avs_anterior));
        $ventas_avs_anterior=collect($ventas_avs_anterior);

        $ventas_avs=DB::select(DB::raw($sql_ventas_avs_actual));
        $ventas_avs=collect($ventas_avs);

        $cont_ambas=DB::select(DB::raw($sql_cont_ambas));
        $cont_ambas=collect($cont_ambas);

        $cont_ambas_anterior=DB::select(DB::raw($sql_cont_ambas_anterior));
        $cont_ambas_anterior=collect($cont_ambas_anterior);

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
        $avs_array=[];
        foreach($ventas_avs as $mes)
        {
            $avs_array[]=[
                                    'periodo'=>$mes->periodo,
                                    'act'=>$mes->act,
                                    'monto_act'=>$mes->monto_act,
                                    'aep'=>$mes->aep,
                                    'monto_aep'=>$mes->monto_aep,
                                  ];
        }
        $avs_anterior_array=[];
        foreach($ventas_avs_anterior as $mes)
        {
            $avs_anterior_array[]=[
                                    'periodo'=>$mes->periodo,
                                    'act'=>$mes->act,
                                    'monto_act'=>$mes->monto_act,
                                    'aep'=>$mes->aep,
                                    'monto_aep'=>$mes->monto_aep,
                                  ];
        }

        $cont_ambas_array=[];
        foreach($cont_ambas as $mes)
        {
            $cont_ambas_array[]=[
                                    'periodo'=>$mes->periodo,
                                    'contribucion'=>$mes->contribucion,
                                  ];
        }
        $cont_ambas_anterior_array=[];
        foreach($cont_ambas_anterior as $mes)
        {
            $cont_ambas_anterior_array[]=[
                                    'periodo'=>$mes->periodo,
                                    'contribucion'=>$mes->contribucion,
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
        return view('dashboard_socios_comparativo',[
                                'periodo'=>$periodo,
                                'meses'=>$meses,
                                'año'=>$año,
                                'año_anterior'=>$año_anterior,
                                'datos_año_anterior'=>$datos_año_anterior,
                                'datos_año'=>$datos_año,
                                'datos_año_anterior_array'=>$año_anterior_array,
                                'datos_año_array'=>$año_array,
                                'datos_avs'=>$ventas_avs,
                                'datos_avs_anterior'=>$ventas_avs_anterior,
                                'avs_array'=>$avs_array,
                                'avs_anterior_array'=>$avs_anterior_array,
                                'contribucion_ambas'=>$cont_ambas,
                                'contribucion_ambas_anterior'=>$cont_ambas_anterior,
                                'contribucion_ambas_array'=>$cont_ambas_array,
                                'contribucion_ambas_anterior_array'=>$cont_ambas_anterior_array
        ]);
    }
}
