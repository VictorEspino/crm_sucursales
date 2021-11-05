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

    public function setCargaId($id)
    {
        $this->carga_id=$id;
    }
    public function setSucursales($sucursales)
    {
        $this->sucursales=$sucursales;
    }

    public function model(array $row)
    {

        $resultado_rentabilidad=$this->getRentabilidad($row['tipo'],$row['importe']);


        return new ErpTransaccion([
            'no_venta'=>$row['venta'],
            'empleado'=>$row['empleado'],
            'fecha'=>$row['fecha'],
            'region'=>$row['region'],
            'pdv'=>$row['almacen'],
            'udn'=>$this->sucursales[$row['almacen']],
            'tipo'=>$row['tipo'],
            'importe'=>$row['importe'],
            'ingreso'=>$resultado_rentabilidad['ingreso'],
            'costo_venta'=>$resultado_rentabilidad['costo'],
            'bracket'=>$resultado_rentabilidad['bracket'],
            'tipo_estandar'=>$resultado_rentabilidad['tipo_estandar'],
            'descripcion'=>$row['descripcion'],
            'cliente'=>$row['cliente'],
            'dn'=>$row['numero_dn'],
            'servicio'=>$row['servicio'],
            'producto'=>$row['producto'],
            'carga_id'=>$this->carga_id,
            'empleado_carga'=>Auth::user()->empleado,
        ]);
    }
    public function rules(): array
    {
        return [
            '*.fecha' => ['required'],
            '*.region' => ['required'],
            '*.almacen' => ['required','exists:sucursals,pdv'],
            '*.tipo' => ['required'],
            '*.importe' => ['required'],
        ];
    }
    public function batchSize(): int
    {
        return 100;
    }
    private function getRentabilidad($tipo,$importe)
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
            $regreso['ingreso']=$this->getIngreso_transaccion($tipo,$this->getBracket_transaccion($importe));
            $regreso['costo']=(1+0.33*30/30.4+0.03)*$this->getCosto_transaccion($tipo,$this->getBracket_transaccion($importe));
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
        if($importe>=0 && $importe<=258) {return(1);}
        if($importe>=259 && $importe<=264) {return(2);}
        if($importe>=265 && $importe<=324) {return(3);}
        if($importe>=325 && $importe<=368) {return(4);}
        if($importe>=369 && $importe<=434) {return(5);}
        if($importe>=435 && $importe<=498) {return(6);}
        if($importe>=499 && $importe<=534) {return(7);}
        if($importe>=535 && $importe<=548) {return(8);}
        if($importe>=549 && $importe<=634) {return(9);}
        if($importe>=635 && $importe<=648) {return(10);}
        if($importe>=649 && $importe<=744) {return(11);}
        if($importe>=745 && $importe<=748) {return(12);}
        if($importe>=749 && $importe<=844) {return(13);}
        if($importe>=845 && $importe<=848) {return(14);}
        if($importe>=849 && $importe<=998) {return(15);}
        if($importe>=999 && $importe<=1044) {return(16);}
        if($importe>=1045 && $importe<=1398) {return(17);}
        if($importe>=1399 && $importe<=1564) {return(18);}
        if($importe>=1565 && $importe<=2264) {return(19);}
        if($importe>=2265 && $importe<=2884) {return(20);}
        if($importe>=2885 && $importe<=4184) {return(21);}
        if($importe>=4185 && $importe<=5504) {return(22);}
        if($importe>=5505 && $importe<=20000) {return(23);}
    }
    private function getIngreso_transaccion($tipo,$bracket)
    {
        if($tipo=="Activación" || $tipo=="Activacion" ||
           $tipo=="Activación Empresarial" || $tipo=="Activacion Empresarial"
          )
        {
            if($bracket==1){return(1400);}
            if($bracket==2){return(1555);}
            if($bracket==3){return(1600);}
            if($bracket==4){return(2200);}
            if($bracket==5){return(2430);}
            if($bracket==6){return(2700);}
            if($bracket==7){return(3065);}
            if($bracket==8){return(3000);}
            if($bracket==9){return(3065);}
            if($bracket==10){return(3500);}
            if($bracket==11){return(3566);}
            if($bracket==12){return(4000);}
            if($bracket==13){return(4019);}
            if($bracket==14){return(4500);}
            if($bracket==15){return(4519);}
            if($bracket==16){return(5280);}
            if($bracket==17){return(5500);}
            if($bracket==18){return(7204);}
            if($bracket==19){return(8000);}
            if($bracket==20){return(11500);}
            if($bracket==21){return(14500);}
            if($bracket==22){return(21000);}
            if($bracket==23){return(27500);}
        }
        if($tipo=="Renovación" || $tipo=="Renovacion"||
           $tipo=="Renovación Empresarial" || $tipo=="Renovacion Empresarial"
          )
        {
            if($bracket==1){return(900);}
            if($bracket==2){return(1055);}
            if($bracket==3){return(1100);}
            if($bracket==4){return(1700);}
            if($bracket==5){return(1930);}
            if($bracket==6){return(2200);}
            if($bracket==7){return(2565);}
            if($bracket==8){return(2500);}
            if($bracket==9){return(2565);}
            if($bracket==10){return(3000);}
            if($bracket==11){return(3066);}
            if($bracket==12){return(3500);}
            if($bracket==13){return(3519);}
            if($bracket==14){return(4000);}
            if($bracket==15){return(4019);}
            if($bracket==16){return(4780);}
            if($bracket==17){return(5000);}
            if($bracket==18){return(6704);}
            if($bracket==19){return(7500);}
            if($bracket==20){return(11000);}
            if($bracket==21){return(14000);}
            if($bracket==22){return(20500);}
            if($bracket==23){return(27000);}
        }
        if($tipo=="Activación Equipo Propio" || $tipo=="Activacion Equipo Propio")
        {
            if($bracket==1){return(900);}
            if($bracket==2){return(1055);}
            if($bracket==3){return(1100);}
            if($bracket==4){return(1700);}
            if($bracket==5){return(1930);}
            if($bracket==6){return(2200);}
            if($bracket==7){return(2565);}
            if($bracket==8){return(2500);}
            if($bracket==9){return(2565);}
            if($bracket==10){return(3000);}
            if($bracket==11){return(3066);}
            if($bracket==12){return(3500);}
            if($bracket==13){return(3519);}
            if($bracket==14){return(4000);}
            if($bracket==15){return(4019);}
            if($bracket==16){return(4780);}
            if($bracket==17){return(5000);}
            if($bracket==18){return(6704);}
            if($bracket==19){return(7500);}
            if($bracket==20){return(11000);}
            if($bracket==21){return(14000);}
            if($bracket==22){return(20500);}
            if($bracket==23){return(27000);}
        }
        if($tipo=="Renovación Equipo Propio" || $tipo=="Renovacion Equipo Propio")
        {
            if($bracket==1){return(450);}
            if($bracket==2){return(528);}
            if($bracket==3){return(550);}
            if($bracket==4){return(850);}
            if($bracket==5){return(965);}
            if($bracket==6){return(1100);}
            if($bracket==7){return(1283);}
            if($bracket==8){return(1250);}
            if($bracket==9){return(1283);}
            if($bracket==10){return(1500);}
            if($bracket==11){return(1533);}
            if($bracket==12){return(1750);}
            if($bracket==13){return(1760);}
            if($bracket==14){return(2000);}
            if($bracket==15){return(2010);}
            if($bracket==16){return(2390);}
            if($bracket==17){return(2500);}
            if($bracket==18){return(3352);}
            if($bracket==19){return(3750);}
            if($bracket==20){return(5500);}
            if($bracket==21){return(7000);}
            if($bracket==22){return(10250);}
            if($bracket==23){return(13500);}
        }
    }
    private function getBracket_addon($importe)
    {
        if($importe>=0 && $importe<=99) {return(1);}
        if($importe>=100 && $importe<=199) {return(2);}
        if($importe>=200 && $importe<=20000) {return(3);}
    }
    private function getIngreso_addon($tipo,$bracket)
    {
        if($bracket==1){return(129.31);}
        if($bracket==2){return(258.62);}
        if($bracket==3){return(517.24);}
    }
    private function getBracket_seguro($importe)
    {
        if($importe>=0 && $importe<=98) {return(1);}
        if($importe>=99 && $importe<=138) {return(2);}
        if($importe>=139 && $importe<=178) {return(3);}
        if($importe>=179 && $importe<=198) {return(4);}
        if($importe>=199 && $importe<=238) {return(5);}
        if($importe>=239 && $importe<=20000) {return(6);}
    }
    private function getIngreso_seguro($tipo,$bracket)
    {

        if($bracket==1){return(178.45);}
        if($bracket==2){return(256.03);}
        if($bracket==3){return(359.48);}
        if($bracket==4){return(462.93);}
        if($bracket==5){return(514.66);}
        if($bracket==6){return(618.10);}
    }
    private function getCosto_transaccion($tipo,$bracket)
    {
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
            if($bracket==1){return(93);}
            if($bracket==2){return(108.5);}
            if($bracket==3){return(109);}
            if($bracket==4){return(179);}
            if($bracket==5){return(203);}
            if($bracket==6){return(240.5);}
            if($bracket==7){return(279.5);}
            if($bracket==8){return(275.23275862069);}
            if($bracket==9){return(282.5);}
            if($bracket==10){return(329.879310344828);}
            if($bracket==11){return(338);}
            if($bracket==12){return(385.525862068965);}
            if($bracket==13){return(388);}
            if($bracket==14){return(440.172413793103);}
            if($bracket==15){return(443.5);}
            if($bracket==16){return(527.5);}
            if($bracket==17){return(550.465517241379);}
            if($bracket==18){return(739);}
            if($bracket==19){return(824.698275862069);}
            if($bracket==20){return(941);}
            if($bracket==21){return(1202);}
            if($bracket==22){return(1764);}
            if($bracket==23){return(2326);}
        }
    }
    private function getCosto_seguro($tipo,$bracket)
    {

        if($bracket==1){return(87);}
        if($bracket==2){return(87);}
        if($bracket==3){return(87);}
        if($bracket==4){return(87);}
        if($bracket==5){return(87);}
        if($bracket==6){return(87);}
    }
    private function getCosto_addon($tipo,$bracket)
    {
        if($bracket==1){return(21.55);}
        if($bracket==2){return(43.10);}
        if($bracket==3){return(86.21);}
    }
}
