<x-app-layout>
    <x-slot name="header">
            {{ __('Resumen periodo') }}
    </x-slot>
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Resumen periodo - {{$periodo}}</div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
        </div> <!--FIN ENCABEZADO-->
        <div class="pt-4 px-4 text-base font-semibold">{{$titulo}}</div>
        <div class="px-4 pb-4 text-sm font-semibold">Dias transcurridos: {{$transcurridos}}</div>
        <div class="px-4 pb-4 text-sm font-semibold">Vista Mensual</div>
        <div class="w-full flex justify-center rounded-b-lg bg-white px-4 flex flex-row">
            <div class="w-10/12 flex justify-center">
                <table class="table-auto">
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1">Concepto</td>
                        <td class="px-1 py-1"><center>Cuota</td>
                        <td class="px-1 py-1"><center>Avance (uds)</td>
                        <td class="px-1 py-1"><center>Avance (%)</td>
                        <td class="px-1 py-1"><center>Diferencia</td>
                        <td class="px-1 py-1"><center>Tendencia</td>
                        <td class="px-1 py-1"><center>Ticket Promedio</td>
                        <td class="px-1 py-1"><center>Mix Venta</td>
                    </tr>
                    <tr class="bg-green-200 text-base font-semibold">
                        <td class="px-1">Activaciones</td>
                        <td class=""><center>{{number_format($ac+$as,0)}}</td>
                        <td class=""><center>{{number_format($av_ac+$av_as,0)}}</td>
                        <td class=""><center>{{number_format(intval($ac+$as)>0?100*($av_ac+$av_as)/($ac+$as):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($ac+$as)-($av_ac+$av_as)),0)}}</td>
                        <td class=""><center>{{intval($ac+$as)>0?number_format((100*$total_dias*($av_ac+$av_as)/$transcurridos)/($ac+$as),0):0}}%</td>
                        <td class=""><center>${{intval($av_ac+$av_as)>0?number_format(($rp_ac*$av_ac+$rp_as*$av_as)/($av_ac+$av_as),0):0}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos)>0?number_format(100*($av_ac+$av_as)/$total_movimientos,0):0}}%</td>
                        -->    
                        <td class=""><center>100%</td>
                    </tr> 
                    <tr class="text-sm">
                        <td class="px-4">CON Equipo</td>
                        <td class=""><center>{{number_format($ac,0)}}</td>
                        <td class=""><center>{{number_format($av_ac,0)}}</td>
                        <td class=""><center>{{number_format(intval($ac)>0?100*$av_ac/$ac:0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*($ac-$av_ac),0)}}</td>
                        <td class=""><center>{{intval($ac)>0?number_format((100*$total_dias*$av_ac/$transcurridos)/$ac,0):0}}%</td>
                        <td class=""><center>${{number_format($rp_ac,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos)>0?number_format(100*$av_ac/$total_movimientos,0):0}}%</td>
                        -->
                        <td class=""><center>{{intval($av_ac+$av_as)>0?number_format(100*$av_ac/($av_ac+$av_as),0):0}}%</td>
                    </tr> 
                    <tr class="bg-gray-100 text-sm">
                        <td class="px-4">SIN Equipo</td>
                        <td class=""><center>{{number_format($as,0)}}</td>
                        <td class=""><center>{{number_format($av_as,0)}}</td>
                        <td class=""><center>{{number_format(intval($as)>0?100*$av_as/$as:0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*($as-$av_as),0)}}</td>
                        <td class=""><center>{{intval($as)>0?number_format((100*$total_dias*$av_as/$transcurridos)/$as,0):0}}%</td>
                        <td class=""><center>${{number_format($rp_as,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos)>0?number_format(100*$av_as/$total_movimientos,0):0}}%</td>
                        -->   
                        <td class=""><center>{{intval($av_ac+$av_as)>0?number_format(100*$av_as/($av_ac+$av_as),0):0}}%</td>
                    </tr>
                    <tr class="bg-green-200 font-semibold">
                        <td class="px-1">Renovaciones</td>
                        <td class=""><center>{{number_format($rc+$rs,0)}}</td>
                        <td class=""><center>{{number_format($av_rc+$av_rs,0)}}</td>
                        <td class=""><center>{{number_format(intval($rc+$rs)>0?100*($av_rc+$av_rs)/($rc+$rs):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($rc+$rs)-($av_rc+$av_rs)),0)}}</td>
                        <td class=""><center>{{intval($rc+$rs)>0?number_format((100*$total_dias*($av_rc+$av_rs)/$transcurridos)/($rc+$rs),0):0}}%</td>
                        <td class=""><center>${{intval($av_rc+$av_rs)>0?number_format(($rp_rc*$av_rc+$rp_rs*$av_rs)/($av_rc+$av_rs),0):0}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos)>0?number_format(100*($av_rc+$av_rs)/$total_movimientos,0):0}}%</td>
                        -->    
                        <td class=""><center>100%</td>
                    </tr> 
                    <tr class="text-sm">
                        <td class="px-4">CON Equipo</td>
                        <td class=""><center>{{number_format($rc,0)}}</td>
                        <td class=""><center>{{number_format($av_rc,0)}}</td>
                        <td class=""><center>{{number_format(intval($rc)>0?100*$av_rc/$rc:0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*($rc-$av_rc),0)}}</td>
                        <td class=""><center>{{intval($rc)>0?number_format((100*$total_dias*$av_rc/$transcurridos)/$rc,0):0}}%</td>
                        <td class=""><center>${{number_format($rp_rc,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos)>0?number_format(100*$av_rc/$total_movimientos,0):0}}%</td>
                        -->    
                        <td class=""><center>{{intval($av_rc+$av_rs)>0?number_format(100*$av_rc/($av_rc+$av_rs),0):0}}%</td>
                    </tr>
                    <tr class="bg-gray-100 text-sm">
                        <td class="px-4">SIN Equipo</td>
                        <td class=""><center>{{number_format($rs,0)}}</td>
                        <td class=""><center>{{number_format($av_rs,0)}}</td>
                        <td class=""><center>{{number_format(intval($rs)>0?100*$av_rs/$rs:0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*($rs-$av_rs),0)}}</td>
                        <td class=""><center>{{intval($rs)>0?number_format((100*$total_dias*$av_rs/$transcurridos)/$rs,0):0}}%</td>
                        <td class=""><center>${{number_format($rp_rs,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos)>0?number_format(100*$av_rs/$total_movimientos,0):0}}%</td>
                        -->
                        <td class=""><center>{{intval($av_rc+$av_rs)>0?number_format(100*$av_rs/($av_rc+$av_rs),0):0}}%</td>
                    </tr>
                </table>
            </div>
            <div class="w-2/12">
                <span>% Productividad</span><br>
                <span class="p-5 text-5xl font-bold text-gray-700">{{number_format($p_productividad,0)}}%</span>
                <br>Objetivo:{{number_format($minutos_objetivo,0)}}
                <br>Tiempo:{{number_format($tiempo_productivo,0)}}
            </div>
        </div>
        <div class="px-4 pt-4 pb-4 text-sm font-semibold">Primera Quincena del {{$periodo}}-01 al {{$periodo}}-15</div>
        <div class="w-full flex justify-center rounded-b-lg bg-white px-4 flex flex-row">
            <div class="w-10/12 flex justify-center">
                <table class="table-auto">
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1">Concepto</td>
                        <td class="px-1 py-1"><center>Cuota</td>
                        <td class="px-1 py-1"><center>Avance (uds)</td>
                        <td class="px-1 py-1"><center>Avance (%)</td>
                        <td class="px-1 py-1"><center>Diferencia</td>
                        <td class="px-1 py-1"><center>Tendencia</td>
                        <td class="px-1 py-1"><center>Ticket Promedio</td>
                        <td class="px-1 py-1"><center>Mix Venta</td>
                    </tr>
                    <tr class="bg-green-200 text-base font-semibold">
                        <td class="px-1">Activaciones</td>
                        <td class=""><center>{{number_format(($ac_q1+$as_q1),0)}}</td>
                        <td class=""><center>{{number_format($av_ac_q1+$av_as_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($ac_q1+$as_q1)>0?100*($av_ac_q1+$av_as_q1)/($ac_q1+$as_q1):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($ac_q1+$as_q1)-($av_ac_q1+$av_as_q1)),0)}}</td>
                        <td class=""><center>{{intval(($ac_q1+$as_q1))>0?number_format((100*15*($av_ac_q1+$av_as_q1)/($transcurridos>=15?15:$transcurridos))/($ac_q1+$as_q1),0):0}}%</td>
                        <td class=""><center>${{intval($av_ac_q1+$av_as_q1)>0?number_format(($rp_ac_q1*$av_ac_q1+$rp_as_q1*$av_as_q1)/($av_ac_q1+$av_as_q1),0):0}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos_q1)>0?number_format(100*($av_ac_q1+$av_as_q1)/$total_movimientos_q1,0):0}}%</td>
                        -->
                        <td class=""><center>100%</td>
                    </tr> 
                    <tr class="text-sm">
                        <td class="px-4">CON Equipo</td>
                        <td class=""><center>{{number_format($ac_q1,0)}}</td>
                        <td class=""><center>{{number_format($av_ac_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($ac_q1)>0?100*$av_ac_q1/($ac_q1):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($ac_q1)-$av_ac_q1),0)}}</td>
                        <td class=""><center>{{intval($ac_q1)>0?number_format(100*15*$av_ac_q1/($transcurridos>=15?15:$transcurridos)/($ac_q1),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_ac_q1,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos_q1)>0?number_format(100*$av_ac_q1/$total_movimientos_q1,0):0}}%</td>
                        -->
                        <td class=""><center>{{intval($av_ac_q1+$av_as_q1)>0?number_format(100*$av_ac_q1/($av_ac_q1+$av_as_q1),0):0}}%</td>
                    </tr> 
                    <tr class="bg-gray-100 text-sm">
                        <td class="px-4">SIN Equipo</td>
                        <td class=""><center>{{number_format($as_q1,0)}}</td>
                        <td class=""><center>{{number_format($av_as_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($as_q1)>0?100*$av_as_q1/($as_q1):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($as_q1)-$av_as_q1),0)}}</td>
                        <td class=""><center>{{intval($as_q1)>0?number_format((100*15*$av_as_q1/($transcurridos>=15?15:$transcurridos))/($as_q1),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_as_q1,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos_q1)>0?number_format(100*$av_as_q1/$total_movimientos_q1,0):0}}%</td>
                        -->
                        <td class=""><center>{{intval($av_ac_q1+$av_as_q1)>0?number_format(100*$av_as_q1/($av_ac_q1+$av_as_q1),0):0}}%</td>
                    </tr>
                    <tr class="bg-green-200 text-base font-semibold">
                        <td class="px-1">Renovaciones</td>
                        <td class=""><center>{{number_format(($rc_q1+$rs_q1),0)}}</td>
                        <td class=""><center>{{number_format($av_rc_q1+$av_rs_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval(($rc_q1+$rs_q1))>0?100*($av_rc_q1+$av_rs_q1)/(($rc_q1+$rs_q1)):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($rc_q1+$rs_q1)-($av_rc_q1+$av_rs_q1)),0)}}</td>
                        <td class=""><center>{{intval(($rc_q1+$rs_q1))>0?number_format((100*15*($av_rc_q1+$av_rs_q1)/($transcurridos>=15?15:$transcurridos))/(($rc_q1+$rs_q1)),0):0}}%</td>
                        <td class=""><center>${{intval($av_rc_q1+$av_rs_q1)>0?number_format(($rp_rc_q1*$av_rc_q1+$rp_rs_q1*$av_rs_q1)/($av_rc_q1+$av_rs_q1),0):0}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos_q1)>0?number_format(100*($av_rc_q1+$av_rs_q1)/$total_movimientos_q1,0):0}}%</td>
                        -->
                        <td class=""><center>100%</td>    
                    </tr> 
                    <tr class="text-sm">
                        <td class="px-4">CON Equipo</td>
                        <td class=""><center>{{number_format($rc_q1,0)}}</td>
                        <td class=""><center>{{number_format($av_rc_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($rc_q1)>0?100*$av_rc_q1/($rc_q1):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($rc_q1)-$av_rc_q1),0)}}</td>
                        <td class=""><center>{{intval($rc_q1)>0?number_format(100*15*$av_rc_q1/($transcurridos>=15?15:$transcurridos)/($rc_q1),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_rc_q1,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos_q1)>0?number_format(100*$av_rc_q1/$total_movimientos_q1,0):0}}%</td>
                        -->
                        <td class=""><center>{{intval($av_rc_q1+$av_rs_q1)>0?number_format(100*$av_rc_q1/($av_rc_q1+$av_rs_q1),0):0}}%</td>
                    </tr> 
                    <tr class="bg-gray-100 text-sm">
                        <td class="px-4">SIN Equipo</td>
                        <td class=""><center>{{number_format($rs_q1,0)}}</td>
                            <td class=""><center>{{number_format($av_rs_q1,0)}}</td>
                            <td class=""><center>{{number_format(intval($rs_q1)>0?100*$av_rs_q1/($rs_q1):0,0)}}%</td>
                            <td class=""><center>{{number_format(-1*(($rs_q1)-$av_rs_q1),0)}}</td>
                            <td class=""><center>{{intval($rs_q1)>0?number_format(100*15*$av_rs_q1/($transcurridos>=15?15:$transcurridos)/($rs_q1),0):0}}%</td>
                            <td class=""><center>${{number_format($rp_rs_q1,0)}}</td>
                            <!--
                            <td class=""><center>{{intval($total_movimientos_q1)>0?number_format(100*$av_rs_q1/$total_movimientos_q1,0):0}}%</td>
                            -->
                            <td class=""><center>{{intval($av_rc_q1+$av_rs_q1)>0?number_format(100*$av_rs_q1/($av_rc_q1+$av_rs_q1),0):0}}%</td>
                    </tr>
                </table>
            </div>
            <div class="w-2/12">
                <span>% Productividad</span><br>
                <span class="p-5 text-5xl font-bold text-gray-700">{{number_format($p_productividad_q1,0)}}%</span>
                <br>Objetivo:{{number_format($minutos_objetivo_q1,0)}}
                <br>Tiempo:{{number_format($tiempo_productivo_q1,0)}}
            </div>
        </div>
        <?php                        
            $rp_ac_q2=($av_ac-$av_ac_q1)>0?($rp_ac*$av_ac-$rp_ac_q1*$av_ac_q1)/($av_ac-$av_ac_q1):0;
            $rp_as_q2=($av_as-$av_as_q1)>0?($rp_as*$av_as-$rp_as_q1*$av_as_q1)/($av_as-$av_as_q1):0;
            $rp_rc_q2=($av_rc-$av_rc_q1)>0?($rp_rc*$av_rc-$rp_rc_q1*$av_rc_q1)/($av_rc-$av_rc_q1):0;
            $rp_rs_q2=($av_rs-$av_rs_q1)>0?($rp_rs*$av_rs-$rp_rs_q1*$av_rs_q1)/($av_rs-$av_rs_q1):0;

            $rp_a_q2=(($av_ac-$av_ac_q1)+($av_as-$av_as_q1))>0?(($rp_ac*$av_ac-$rp_ac_q1*$av_ac_q1)+($rp_as*$av_as-$rp_as_q1*$av_as_q1))/(($av_ac-$av_ac_q1)+($av_as-$av_as_q1)):0;
            $rp_r_q2=(($av_rc-$av_rc_q1)+($av_rs-$av_rs_q1))>0?(($rp_rc*$av_rc-$rp_rc_q1*$av_rc_q1)+($rp_rs*$av_rs-$rp_rs_q1*$av_rs_q1))/(($av_rc-$av_rc_q1)+($av_rs-$av_rs_q1)):0;
        ?>
        <div class="px-4 pt-4 pb-4 text-sm font-semibold">Segunda Quincena del {{$periodo}}-16 al {{$periodo}}-{{$total_dias}}</div>
        <div class="w-full flex justify-center rounded-b-lg bg-white px-4 flex flex-row pb-6">
            <div class="w-10/12 flex justify-center">
                <table class="table-auto">
                    <tr class="bg-gray-200 text-sm font-bold">
                        <td class="px-1 py-1">Concepto</td>
                        <td class="px-1 py-1"><center>Cuota</td>
                        <td class="px-1 py-1"><center>Avance (uds)</td>
                        <td class="px-1 py-1"><center>Avance (%)</td>
                        <td class="px-1 py-1"><center>Diferencia</td>
                        <td class="px-1 py-1"><center>Tendencia</td>
                        <td class="px-1 py-1"><center>Ticket Promedio</td>
                        <td class="px-1 py-1"><center>Mix Venta</td>
                    </tr>
                    <tr class="bg-green-200 text-base font-semibold">
                        <td class="px-1">Activaciones</td>
                        <td class=""><center>{{number_format(($ac_q2+$as_q2),0)}}</td>
                        <td class=""><center>{{number_format(($av_ac+$av_as)-($av_ac_q1+$av_as_q1),0)}}</td>
                        <td class=""><center>{{number_format(intval(($ac_q2+$as_q2))>0?100*(($av_ac+$av_as)-($av_ac_q1+$av_as_q1))/(($ac_q2+$as_q2)):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($ac_q2+$as_q2)-(($av_ac+$av_as)-($av_ac_q1+$av_as_q1))),0)}}</td>
                        <td class=""><center>{{intval(($ac_q2+$as_q2))>0 && $transcurridos-15>0?number_format((100*($total_dias-15)*(($av_ac+$av_as)-($av_ac_q1+$av_as_q1))/($transcurridos-15>0?$transcurridos-15:0))/(($ac_q2+$as_q2)),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_a_q2,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos-$total_movimientos_q1)>0?number_format(100*(($av_ac+$av_as)-($av_ac_q1+$av_as_q1))/($total_movimientos-$total_movimientos_q1),0):0}}%</td>
                        -->
                        <td class=""><center>100%</td>
                    </tr> 
                    <tr class="text-sm">
                        <td class="px-4">CON Equipo</td>
                        <td class=""><center>{{number_format($ac_q2,0)}}</td>
                        <td class=""><center>{{number_format($av_ac-$av_ac_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($ac_q2)>0?100*($av_ac-$av_ac_q1)/($ac_q2):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($ac_q2)-($av_ac-$av_ac_q1)),0)}}</td>
                        <td class=""><center>{{intval($ac_q2)>0 && $transcurridos-15>0?number_format((100*($total_dias-15)*($av_ac-$av_ac_q1)/($transcurridos-15>0?$transcurridos-15:0))/($ac_q2),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_ac_q2,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos-$total_movimientos_q1)>0?number_format(100*($av_ac-$av_ac_q1)/($total_movimientos-$total_movimientos_q1),0):0}}%</td>
                        -->
                        <td class=""><center>{{intval(($av_ac-$av_ac_q1)+($av_as-$av_as_q1))>0?number_format(100*($av_ac-$av_ac_q1)/(($av_ac-$av_ac_q1)+($av_as-$av_as_q1)),0):0}}%</td>
                    </tr> 
                    <tr class="bg-gray-100 text-sm">
                        <td class="px-4">SIN Equipo</td>
                        <td class=""><center>{{number_format($as_q2,0)}}</td>
                        <td class=""><center>{{number_format($av_as-$av_as_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($as_q2)>0?100*($av_as-$av_as_q1)/($as_q2):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($as_q2)-($av_as-$av_as_q1)),0)}}</td>
                        <td class=""><center>{{intval($as_q2)>0 && $transcurridos-15>0?number_format((100*($total_dias-15)*($av_as-$av_as_q1)/($transcurridos-15>0?$transcurridos-15:0))/($as_q2),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_as_q2,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos-$total_movimientos_q1)>0?number_format(100*($av_as-$av_as_q1)/($total_movimientos-$total_movimientos_q1),0):0}}%</td>
                        -->
                        <td class=""><center>{{intval(($av_ac-$av_ac_q1)+($av_as-$av_as_q1))>0?number_format(100*($av_as-$av_as_q1)/(($av_ac-$av_ac_q1)+($av_as-$av_as_q1)),0):0}}%</td>
                    </tr>
                    <tr class="bg-green-200 text-base font-semibold">
                        <td class="px-1">Renovaciones</td>
                        <td class=""><center>{{number_format(($rc_q2+$rs_q2),0)}}</td>
                        <td class=""><center>{{number_format(($av_rc+$av_rs)-($av_rc_q1+$av_rs_q1),0)}}</td>
                        <td class=""><center>{{number_format(intval(($ac_q2+$as_q2))>0?100*(($av_rc+$av_rs)-($av_rc_q1+$av_rs_q1))/(($rc_q2+$rs_q2)):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($rc_q2+$rs_q2)-(($av_rc+$av_rs)-($av_rc_q1+$av_rs_q1))),0)}}</td>
                        <td class=""><center>{{intval(($rc_q2+$rs_q2))>0 && $transcurridos-15>0?number_format((100*($total_dias-15)*(($av_rc+$av_rs)-($av_rc_q1+$av_rs_q1))/($transcurridos-15>0?$transcurridos-15:0))/(($rc_q2+$rs_q2)),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_r_q2,0)}}</td>
                        <!--
                        <td class=""><center>{{intval($total_movimientos-$total_movimientos_q1)>0?number_format(100*(($av_rc+$av_rs)-($av_rc_q1+$av_rs_q1))/($total_movimientos-$total_movimientos_q1),0):0}}%</td>
                        -->
                        <td class=""><center>100%</td>
                    </tr> 
                    <tr class="text-sm">
                        <td class="px-4">CON Equipo</td>
                        <td class=""><center>{{number_format($rc_q2,0)}}</td>
                        <td class=""><center>{{number_format($av_rc-$av_rc_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($rc_q2)>0?100*($av_rc-$av_rc_q1)/($rc_q2):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($rc_q2)-($av_rc-$av_rc_q1)),0)}}</td>
                        <td class=""><center>{{intval($rc_q2)>0 && $transcurridos-15>0?number_format((100*($total_dias-15)*($av_rc-$av_rc_q1)/($transcurridos-15>0?$transcurridos-15:0))/($rc_q2),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_rc_q2,0)}}</td>
                        <td class=""><center>{{intval(($av_rc-$av_rc_q1)+($av_rs-$av_rs_q1))>0?number_format(100*($av_rc-$av_rc_q1)/(($av_rc-$av_rc_q1)+($av_rs-$av_rs_q1)),0):0}}%</td>
                    </tr> 
                    <tr class="bg-gray-100 text-sm">
                        <td class="px-4">SIN Equipo</td>
                        <td class=""><center>{{number_format($rs_q2,0)}}</td>
                        <td class=""><center>{{number_format($av_rs-$av_rs_q1,0)}}</td>
                        <td class=""><center>{{number_format(intval($rs_q2)>0?100*($av_rs-$av_rs_q1)/($rs_q2):0,0)}}%</td>
                        <td class=""><center>{{number_format(-1*(($rs_q2)-($av_rs-$av_rs_q1)),0)}}</td>
                        <td class=""><center>{{intval($rs_q2)>0 && $transcurridos-15>0?number_format((100*($total_dias-15)*($av_rs-$av_rs_q1)/($transcurridos-15>0?$transcurridos-15:0))/($rs_q2),0):0}}%</td>
                        <td class=""><center>${{number_format($rp_rs_q2,0)}}</td>
                        <td class=""><center>{{intval(($av_rc-$av_rc_q1)+($av_rs-$av_rs_q1))>0?number_format(100*($av_rs-$av_rs_q1)/(($av_rc-$av_rc_q1)+($av_rs-$av_rs_q1)),0):0}}%</td>
                    </tr>
                </table>
            </div>
            <div class="w-2/12">
                <span>% Productividad</span><br>
                <span class="p-5 text-5xl font-bold text-gray-700">{{number_format($p_productividad_q2,0)}}%</span>
                <br>Objetivo:{{number_format($minutos_objetivo_q2,0)}}
                <br>Tiempo:{{number_format($tiempo_productivo_q2,0)}}
            </div>
        </div>
        @if($origen=="G" || $origen=="R" || $origen=="D")
        <div class="w-full flex flex-col pb-4">
            <div class="w-full p-4 bg-gray-200 text-gray-700 text-xl font-semibold border-b ">Detalles de : </div>
            @foreach($detalles as $detalle)
            <div class="w-full text-base font-semibold flex justify-center"><a href="/dashboard_resumen_periodo/{{$periodo}}/{{($origen=="G"?'E':($origen=="R"?"G":"R"))}}/{{$detalle->llave}}/{{$detalle->value}}">{{$detalle->value}}</a></div>
            @endforeach
        </div>
        @endif
    </div>    
</x-app-layout>
