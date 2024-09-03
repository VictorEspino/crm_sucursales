<?php

namespace App\Imports;

use App\Models\ErpTransaccion;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class ErpTransaccionesImport implements ToModel,WithHeadingRow,WithValidation,WithBatchInserts
{
    use Importable;
    private $carga_id;
    private $sucursales;
    private $regiones;

    public function setCargaId($id)
    {
        $this->carga_id=$id;
    }
    public function setSucursales($sucursales)
    {
        $this->sucursales=$sucursales;
    }
    public function setRegiones($regiones)
    {
        $this->regiones=$regiones;
    }

    public function model(array $row)
    {

        $resultado_rentabilidad=$this->getRentabilidad($row['tipo'],$row['importe'],$row['servicio'],intval($row['plazo_en_meses']));
        $udn=0;
        $region='SOCIO COMERCIAL';
        $direccion='SOCIOS';
        if($row['region']!='SOCIO COMERCIAL')
        {
            $udn=$this->sucursales[$row['almacen']];
            $region=$this->regiones[$row['almacen']];
            $direccion='SUCURSALES';
        }
        return new ErpTransaccion([
            'no_venta'=>$row['venta'],
            'empleado'=>$row['empleado'],
            'fecha'=>$row['fecha'],
            'region'=>$region,
            'pdv'=>$row['almacen'],
            'udn'=>$udn,
            'tipo'=>$row['tipo'],
            'importe'=>$row['importe'],
            'ingreso'=>$resultado_rentabilidad['ingreso'],
            'costo_venta'=>$resultado_rentabilidad['costo'],
            'bracket'=>$resultado_rentabilidad['bracket'],
            'tipo_estandar'=>$resultado_rentabilidad['tipo_estandar'],
            'descripcion'=>substr($row['descripcion'],0,254),
            'cliente'=>substr($row['cliente'],0,254),
            'dn'=>$row['numero_dn'],
            'servicio'=>$row['servicio'],
            'producto'=>$row['producto'],
            'plazo'=>intval($row['plazo_en_meses']),
            'carga_id'=>$this->carga_id,
            'empleado_carga'=>Auth::user()->empleado,
            'direccion'=>$direccion,
        ]);
    }
    public function rules(): array
    {
        return [
            '*.fecha' => ['required'],
            '*.region' => ['required'],
            '*.almacen' => ['required','exclude_if:*.region,SOCIO COMERCIAL','exists:sucursals,pdv'],
            '*.tipo' => ['required'],
            '*.importe' => ['required'],
        ];
    }
    public function batchSize(): int
    {
        return 100;
    }
    private function getRentabilidad($tipo,$importe,$servicio,$plazo)
    {
        $regreso=array('ingreso'=>0,'costo'=>0,'bracket'=>0,'tipo_estandar'=>'');
        if(
            $tipo=="Activación" || $tipo=="Activacion" ||
            $tipo=="Renovación" || $tipo=="Renovacion" ||
            $tipo=="Activación Equipo Propio" || $tipo=="Activacion Equipo Propio" ||
            $tipo=="Renovación Equipo Propio" || $tipo=="Renovacion Equipo Propio" ||
            $tipo=="Activación Empresarial" || $tipo=="Activacion Empresarial" ||
            $tipo=="Renovación Empresarial" || $tipo=="Renovacion Empresarial"
        )
        {
            if(
                $tipo=="Activación" || $tipo=="Activacion" ||
                $tipo=="Activación Empresarial" || $tipo=="Activacion Empresarial"
            ){$regreso['tipo_estandar']='ACT';}
            if(

                $tipo=="Renovación" || $tipo=="Renovacion" ||
                $tipo=="Renovación Empresarial" || $tipo=="Renovacion Empresarial"
            ){$regreso['tipo_estandar']='REN';}
            if(
                $tipo=="Activación Equipo Propio" || $tipo=="Activacion Equipo Propio"
            ){$regreso['tipo_estandar']='AEP';}
            if(
                $tipo=="Renovación Equipo Propio" || $tipo=="Renovacion Equipo Propio"
            ){$regreso['tipo_estandar']='REP';}




            $regreso['bracket']=$this->getBracket_transaccion($importe);
            $regreso['ingreso']=$this->getIngreso_transaccion($tipo,$this->getBracket_transaccion($importe),$servicio,$plazo,$importe);
            $regreso['costo']=(1+0.33*30/30.4+0.03)*$this->getCosto_transaccion($tipo,$this->getBracket_transaccion_costo($importe),$servicio,$plazo,$importe);
        }
        if($tipo=="ADD ON")
        {
            $regreso['bracket']=$this->getBracket_addon($importe);
            $regreso['tipo_estandar']='ADD';
            $regreso['ingreso']=$this->getIngreso_addon($tipo,$this->getBracket_addon($importe));   
            $regreso['costo']=(1+0.33*30/30.4+0.03)*$this->getCosto_addon($tipo,$this->getBracket_addon($importe));   
        }
        if($tipo=="Protección de equipo" || $tipo=="Proteccion de equipo")
        {
            $regreso['bracket']=$this->getBracket_seguro($importe);
            $regreso['tipo_estandar']='SEG';
            $regreso['ingreso']=$this->getIngreso_seguro($tipo,$this->getBracket_seguro($importe));   
            $regreso['costo']=(1+0.33*30/30.4+0.03)*$this->getCosto_seguro($tipo,$this->getBracket_seguro($importe));   
        }
        return($regreso);
    }
    private function getBracket_transaccion($importe)
    {
        if($importe>=0 && $importe<=298.999999) {return(1);}
        if($importe>=299 && $importe<=398.999999) {return(2);}
        if($importe>=399 && $importe<=498.999999) {return(3);}
        if($importe>=499 && $importe<=598.999999) {return(4);}
        if($importe>=599 && $importe<=648.999999) {return(5);}
        if($importe>=649 && $importe<=658.999999) {return(6);}
        if($importe>=659 && $importe<=748.999999) {return(7);}
        if($importe>=749 && $importe<=758.999999) {return(8);}
        if($importe>=759 && $importe<=848.999999) {return(9);}
        if($importe>=849 && $importe<=948.999999) {return(10);}
        if($importe>=949 && $importe<=1048.999999) {return(11);}
        if($importe>=1049 && $importe<=1198.999999) {return(12);}
        if($importe>=1199 && $importe<=1498.999999) {return(13);}
        if($importe>=1499 && $importe<=49999.999999) {return(14);}
        return(0);
    }
    private function getBracket_transaccion_costo($importe)
    {
        if($importe>=0 && $importe<=258.999999) {return(1);}
        if($importe>=259 && $importe<=264.999999) {return(2);}
        if($importe>=265 && $importe<=324.999999) {return(3);}
        if($importe>=325 && $importe<=368.999999) {return(4);}
        if($importe>=369 && $importe<=434.999999) {return(5);}
        if($importe>=435 && $importe<=498.999999) {return(6);}
        if($importe>=499 && $importe<=534.999999) {return(7);}
        if($importe>=535 && $importe<=548.999999) {return(8);}
        if($importe>=549 && $importe<=634.999999) {return(9);}
        if($importe>=635 && $importe<=648.999999) {return(10);}
        if($importe>=649 && $importe<=744.999999) {return(11);}
        if($importe>=745 && $importe<=748.999999) {return(12);}
        if($importe>=749 && $importe<=844.999999) {return(13);}
        if($importe>=845 && $importe<=848.999999) {return(14);}
        if($importe>=849 && $importe<=998.999999) {return(15);}
        if($importe>=999 && $importe<=1044.999999) {return(16);}
        if($importe>=1045 && $importe<=1398.999999) {return(17);}
        if($importe>=1399 && $importe<=1564.999999) {return(18);}
        if($importe>=1565 && $importe<=2264.999999) {return(19);}
        if($importe>=2265 && $importe<=2884.999999) {return(20);}
        if($importe>=2885 && $importe<=4184.999999) {return(21);}
        if($importe>=4185 && $importe<=5504.999999) {return(22);}
        if($importe>=5505 && $importe<=20000.999999) {return(23);}
        return(0);
    }

    private function getIngreso_transaccion($tipo,$bracket,$servicio,$plazo,$importe)
    {
        if(strpos($servicio,"SIMPLE")!==false)
        {
            if($plazo==6) return(75);
            if($plazo>=12 and $plazo<18) return(($importe/1.16/1.03)*1.5);
            if($plazo>=18 and $plazo<24) return(($importe/1.16/1.03)*2.5);
            if($plazo>=24) return(($importe/1.16/1.03)*3);
        }
        if($tipo=="Activación" || $tipo=="Activacion" ||
           $tipo=="Activación Empresarial" || $tipo=="Activacion Empresarial"
          )       
          {
            if($bracket==1){return(458);}
            if($bracket==2){return(1718);}
            if($bracket==3){return(2587);}
            if($bracket==4){return(2995);}
            if($bracket==5){return(3530);}
            if($bracket==6){return(3533);}
            if($bracket==7){return(3580);}
            if($bracket==8){return(4038);}
            if($bracket==9 || strpos($servicio,"TITANIO")!== false){return(4085);}
            if($bracket==10){return(4489);}
            if($bracket==11){return(4993);}
            if($bracket==12){return(5758);}
            if($bracket==13){return(6245);}
            if($bracket==14){return(7683);}
        }
        if($tipo=="Renovación" || $tipo=="Renovacion"||
           $tipo=="Renovación Empresarial" || $tipo=="Renovacion Empresarial"||
           $tipo=="Activación Equipo Propio" || $tipo=="Activacion Equipo Propio"
          )
        {
            if($bracket==1){return(0);}
            if($bracket==2){return(1218);}
            if($bracket==3){return(2087);}
            if($bracket==4){return(2495);}
            if($bracket==5){return(3030);}
            if($bracket==6){return(3033);}
            if($bracket==7){return(3080);}
            if($bracket==8){return(3538);}
            if($bracket==9 || strpos($servicio,"TITANIO")!== false){return(3585);}
            if($bracket==10){return(3989);}
            if($bracket==11){return(4493);}
            if($bracket==12){return(5258);}
            if($bracket==13){return(5745);}
            if($bracket==14){return(7183);}
        }
        if($tipo=="Renovación Equipo Propio" || $tipo=="Renovacion Equipo Propio")
        {
            return(0);
        }
        return(0);
    }
    private function getBracket_addon($importe)
    {
        if($importe>=0 && $importe<=99) {return(1);}
        if($importe>=100 && $importe<=199) {return(2);}
        if($importe>=200 && $importe<=20000) {return(3);}
        return(0);
    }
    private function getIngreso_addon($tipo,$bracket)
    {
        if($bracket==1){return(129.31);}
        if($bracket==2){return(258.62);}
        if($bracket==3){return(517.24);}
        return(0);
    }
    private function getBracket_seguro($importe)
    {
        if($importe>=0 && $importe<=98) {return(1);}
        if($importe>=99 && $importe<=138) {return(2);}
        if($importe>=139 && $importe<=178) {return(3);}
        if($importe>=179 && $importe<=198) {return(4);}
        if($importe>=199 && $importe<=238) {return(5);}
        if($importe>=239 && $importe<=20000) {return(6);}
        return(0);
    }
    private function getIngreso_seguro($tipo,$bracket)
    {

        if($bracket==1){return(178.45);}
        if($bracket==2){return(256.03);}
        if($bracket==3){return(359.48);}
        if($bracket==4){return(462.93);}
        if($bracket==5){return(514.66);}
        if($bracket==6){return(618.10);}
        return(0);
    }
    private function getCosto_transaccion($tipo,$bracket,$servicio,$plazo,$importe)
    {
        if(strpos($servicio,"SIMPLE")!==false)
        {
            if($plazo==6) return(38);
            if($plazo>=12 and $plazo<18) return(($importe/1.16/1.03)*0.8);
            if($plazo>=18 and $plazo<24) return(($importe/1.16/1.03)*1.3);
            if($plazo>=24) return(($importe/1.16/1.03)*1.6);
        }
        if($tipo=="Activación" || $tipo=="Activacion" ||
           $tipo=="Activación Empresarial" || $tipo=="Activacion Empresarial"
          )
        {
            if($bracket==1){return(231);}
            if($bracket==2){return(250);}
            if($bracket==3){return(262);}
            if($bracket==4){return(471);}
            if($bracket==5){return(511);}
            if($bracket==6){return(596);}
            if($bracket==7){return(721);}
            if($bracket==8){return(667.827586206897);}
            if($bracket==9){return(681);}
            if($bracket==10){return(802);}
            if($bracket==11){return(845);}
            if($bracket==12){return(934.758620689655);}
            if($bracket==13){return(939);}
            if($bracket==14){return(1068.72413793103);}
            if($bracket==15){return(1099);}
            if($bracket==16){return(1244);}
            if($bracket==17){return(1335.65517241379);}
            if($bracket==18){return(1697);}
            if($bracket==19){return(2003.48275862069);}
            if($bracket==20){return(2471.68965517241);}
            if($bracket==21){return(3740.03448275862);}
            if($bracket==22){return(5477.58620689655);}
            if($bracket==23){return(7215.13793103448);}
        }
        if($tipo=="Renovación" || $tipo=="Renovacion"||
           $tipo=="Renovación Empresarial" || $tipo=="Renovacion Empresarial"
          )
        {
            if($bracket==1){return(184);}
            if($bracket==2){return(216);}
            if($bracket==3){return(217);}
            if($bracket==4){return(357);}
            if($bracket==5){return(406);}
            if($bracket==6){return(481);}
            if($bracket==7){return(559);}
            if($bracket==8){return(550.465517241379);}
            if($bracket==9){return(564);}
            if($bracket==10){return(660.758620689655);}
            if($bracket==11){return(674);}
            if($bracket==12){return(770.051724137931);}
            if($bracket==13){return(774);}
            if($bracket==14){return(879.344827586207);}
            if($bracket==15){return(884);}
            if($bracket==16){return(1052);}
            if($bracket==17){return(1100.93103448276);}
            if($bracket==18){return(1476);}
            if($bracket==19){return(1650.39655172414);}
            if($bracket==20){return(1882);}
            if($bracket==21){return(2405);}
            if($bracket==22){return(3529);}
            if($bracket==23){return(4653);}
        }
        if($tipo=="Activación Equipo Propio" || $tipo=="Activacion Equipo Propio")
        {
            if($bracket==1){return(152);}
            if($bracket==2){return(178);}
            if($bracket==3){return(180);}
            if($bracket==4){return(295);}
            if($bracket==5){return(335);}
            if($bracket==6){return(397);}
            if($bracket==7){return(462);}
            if($bracket==8){return(456.172413793103);}
            if($bracket==9){return(467);}
            if($bracket==10){return(548.206896551724);}
            if($bracket==11){return(560);}
            if($bracket==12){return(638.241379310345);}
            if($bracket==13){return(642);}
            if($bracket==14){return(729.275862068966);}
            if($bracket==15){return(732);}
            if($bracket==16){return(872);}
            if($bracket==17){return(912.344827586207);}
            if($bracket==18){return(1224);}
            if($bracket==19){return(1368.51724137931);}
            if($bracket==20){return(2007.75862068966);}
            if($bracket==21){return(2554.96551724138);}
            if($bracket==22){return(3741.41379310345);}
            if($bracket==23){return(4928.86206896552);}
        }
        if($tipo=="Renovación Equipo Propio" || $tipo=="Renovacion Equipo Propio")
        {
            return(0);
        }
        return(0);
    }
    private function getCosto_seguro($tipo,$bracket)
    {

        if($bracket==1){return(87);}
        if($bracket==2){return(87);}
        if($bracket==3){return(87);}
        if($bracket==4){return(87);}
        if($bracket==5){return(87);}
        if($bracket==6){return(87);}
        return(0);
    }
    private function getCosto_addon($tipo,$bracket)
    {
        if($bracket==1){return(21.55);}
        if($bracket==2){return(43.10);}
        if($bracket==3){return(86.21);}
        return(0);
    }
}
