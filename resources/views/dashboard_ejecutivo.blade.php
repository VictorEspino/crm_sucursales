<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Ejecutivo') }}
    </x-slot>
    <div class="flex flex-col w-full  bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Medicion ejecutiva - {{$periodo}}</div>
            <div class="w-full text-lg font-semibold">{{$titulo}}</div>
            <div class="w-full text-lg font-semibold text-blue-900"><a href="/comentarios" target="_blank">Notas de seguimiento</a></div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
        </div> <!--FIN ENCABEZADO-->

        <?php
        if($origen=="R"||$origen=="D")
        {
    ?>
    <div class="w-full bg-gray-200 p-3 flex flex-col">
        <div class="w-full text-lg font-semibold">Detalles</div>
    </div>
    <div class="w-full bg-white p-3 flex justify-center text-xs">
        <table class="">
            <tr>
                <td class="px-2" colspan=2> </td>
                <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-2" colspan=3><center>Flujo</td>
                <td class="bg-green-300 border border-gray-400 text-gray-600 font-semibold px-2" colspan=3><center>Activaciones</td>
                <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-2" colspan=3><center>Renovaciones</td>
                <td class="bg-green-300 border border-gray-400 text-gray-600 font-semibold px-2" colspan=2><center>Efectividad<br>Tienda</td>
                <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-2" colspan=2><center>Efectividad<br>Generacion</td>
                <td class="bg-green-300 border border-gray-400 text-gray-600 font-semibold px-2" colspan=2><center>Productividad<br>Tienda</td>
                <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-2" colspan=2><center>Productividad<br>Generacion</td>
                <td class="bg-green-300 border border-gray-400 text-gray-600 font-semibold px-2" colspan=1><center>Prod</td>
                <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-2" colspan=2><center>Pendientes<br>Facturacion</td>
                <td class="bg-green-300 border border-gray-400 text-gray-600 font-semibold px-2" colspan=1><center>Ticket<br>Promedio</td>
            <tr>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2" colspan=2></td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Total</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">C/Int</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">%</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Total</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Cuota</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">%</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Total</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Cuota</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">%</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2"># Solic</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">% vs Traf</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2"># Solic</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">% vs Cont</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Ventas</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">% vs Traf</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Ventas</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">% vs Cont</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2"><center>%</center></td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Solic</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Monto<br>Prom</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2"><center>$</center></td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Plantilla</td>
                <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-2">Rentabilid</td>


            </tr>
            <?php
            $color=false;
            foreach($detalles as $detalle)
            {
            ?>
            <tr>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><a href="/dashboard_ejecutivo/{{$periodo}}/{{($origen=="G"?'E':($origen=="R"?"G":"R"))}}/{{$detalle['key']}}/{{$detalle['value']}}">{{$detalle['key']}}</a></td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2">{{$detalle['value']}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['flujo']['flujo'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['flujo']['flujo_intencion'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{intval($detalle['indicadores']['flujo']['flujo'])>0?number_format(100*$detalle['indicadores']['flujo']['flujo_intencion']/$detalle['indicadores']['flujo']['flujo'],0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['ventas']['act_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['cuotas']['ac']+$detalle['indicadores']['cuotas']['asi'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{($detalle['indicadores']['cuotas']['ac']+$detalle['indicadores']['cuotas']['asi'])>0?number_format(100*$detalle['indicadores']['ventas']['act_total']/($detalle['indicadores']['cuotas']['ac']+$detalle['indicadores']['cuotas']['asi']),0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['ventas']['ren_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['cuotas']['rc']+$detalle['indicadores']['cuotas']['rs'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{($detalle['indicadores']['cuotas']['rc']+$detalle['indicadores']['cuotas']['rs'])>0?number_format(100*$detalle['indicadores']['ventas']['ren_total']/($detalle['indicadores']['cuotas']['rc']+$detalle['indicadores']['cuotas']['rs']),0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['solicitudes']['tda_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{$detalle['indicadores']['flujo']['flujo']>0?number_format(100*$detalle['indicadores']['solicitudes']['tda_total']/$detalle['indicadores']['flujo']['flujo'],0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['solicitudes']['dem_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{$detalle['indicadores']['demanda']['demanda_total']>0?number_format(100*$detalle['indicadores']['solicitudes']['dem_total']/$detalle['indicadores']['demanda']['demanda_total'],0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['ventas']['act_con_tda_total']+$detalle['indicadores']['ventas']['act_sin_tda_total']+$detalle['indicadores']['ventas']['ren_con_tda_total']+$detalle['indicadores']['ventas']['ren_sin_tda_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{$detalle['indicadores']['flujo']['flujo']>0?number_format(100*($detalle['indicadores']['ventas']['act_con_tda_total']+$detalle['indicadores']['ventas']['act_sin_tda_total']+$detalle['indicadores']['ventas']['ren_con_tda_total']+$detalle['indicadores']['ventas']['ren_sin_tda_total'])/$detalle['indicadores']['flujo']['flujo'],0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['ventas']['act_con_dem_total']+$detalle['indicadores']['ventas']['act_sin_dem_total']+$detalle['indicadores']['ventas']['ren_con_dem_total']+$detalle['indicadores']['ventas']['ren_sin_dem_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{$detalle['indicadores']['demanda']['demanda_total']>0?number_format(100*($detalle['indicadores']['ventas']['act_con_dem_total']+$detalle['indicadores']['ventas']['act_sin_dem_total']+$detalle['indicadores']['ventas']['ren_con_dem_total']+$detalle['indicadores']['ventas']['ren_sin_dem_total'])/$detalle['indicadores']['demanda']['demanda_total'],0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center><b>{{number_format(intval($detalle['indicadores']['cuotas']['minutos']-$detalle['indicadores']['productividad']['total']['incidencias'])>0?100*$detalle['indicadores']['productividad']['total']['minutos']/($detalle['indicadores']['cuotas']['minutos']-$detalle['indicadores']['productividad']['total']['incidencias']):0,0)}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['pendientes']['pendientes_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>${{intval($detalle['indicadores']['pendientes']['pendientes_total'])>0?number_format($detalle['indicadores']['pendientes']['monto_total']/$detalle['indicadores']['pendientes']['pendientes_total'],0):0}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>${{number_format($detalle['indicadores']['ventas']['act_ticket_total'],0)}}</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{$detalle['indicadores']['cuotas']['ejecutivos']>0?number_format(100*$detalle['indicadores']['activos']['activos']/$detalle['indicadores']['cuotas']['ejecutivos'],0):0}}%</td>
                <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-2"><center>{{number_format($detalle['indicadores']['rentabilidad']['rentabilidad'],0)}}%</td>
            </tr>
            <?php
                $color=!$color;
            }
            ?>
            <tr class="text-sm">
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"></td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2">Total</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['flujo']['flujo'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['flujo']['flujo_intencion'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{intval($indicadores['flujo']['flujo'])>0?number_format(100*$indicadores['flujo']['flujo_intencion']/$indicadores['flujo']['flujo'],0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['ventas']['act_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['cuotas']['ac']+$indicadores['cuotas']['asi'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{($indicadores['cuotas']['ac']+$indicadores['cuotas']['asi'])>0?number_format(100*$indicadores['ventas']['act_total']/($indicadores['cuotas']['ac']+$indicadores['cuotas']['asi']),0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['ventas']['ren_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['cuotas']['rc']+$indicadores['cuotas']['rs'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{($indicadores['cuotas']['rc']+$indicadores['cuotas']['rs'])>0?number_format(100*$indicadores['ventas']['ren_total']/($indicadores['cuotas']['rc']+$indicadores['cuotas']['rs']),0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['solicitudes']['tda_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{$indicadores['flujo']['flujo']>0?number_format(100*$indicadores['solicitudes']['tda_total']/$indicadores['flujo']['flujo'],0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['solicitudes']['dem_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{$indicadores['demanda']['demanda_total']>0?number_format(100*$indicadores['solicitudes']['dem_total']/$indicadores['demanda']['demanda_total'],0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['ventas']['act_con_tda_total']+$indicadores['ventas']['act_sin_tda_total']+$indicadores['ventas']['ren_con_tda_total']+$indicadores['ventas']['ren_sin_tda_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{$indicadores['flujo']['flujo']>0?number_format(100*($indicadores['ventas']['act_con_tda_total']+$indicadores['ventas']['act_sin_tda_total']+$indicadores['ventas']['ren_con_tda_total']+$indicadores['ventas']['ren_sin_tda_total'])/$indicadores['flujo']['flujo'],0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['ventas']['act_con_dem_total']+$indicadores['ventas']['act_sin_dem_total']+$indicadores['ventas']['ren_con_dem_total']+$indicadores['ventas']['ren_sin_dem_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{$indicadores['demanda']['demanda_total']>0?number_format(100*($indicadores['ventas']['act_con_dem_total']+$indicadores['ventas']['act_sin_dem_total']+$indicadores['ventas']['ren_con_dem_total']+$indicadores['ventas']['ren_sin_dem_total'])/$indicadores['demanda']['demanda_total'],0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center><b>{{number_format(intval($indicadores['cuotas']['minutos']-$indicadores['productividad']['total']['incidencias'])>0?100*$indicadores['productividad']['total']['minutos']/($indicadores['cuotas']['minutos']-$indicadores['productividad']['total']['incidencias']):0,0)}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['pendientes']['pendientes_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>${{intval($indicadores['pendientes']['pendientes_total'])>0?number_format($indicadores['pendientes']['monto_total']/$indicadores['pendientes']['pendientes_total'],0):0}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>${{number_format($indicadores['ventas']['act_ticket_total'],0)}}</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{$indicadores['cuotas']['ejecutivos']>0?number_format(100*$indicadores['activos']['activos']/$indicadores['cuotas']['ejecutivos'],0):0}}%</td>
                <td class="bg-gray-700 border border-gray-400 text-gray-200 font-light px-2"><center>{{number_format($indicadores['rentabilidad']['rentabilidad'],0)}}%</td>
            </tr>
        </table>
    </div>
    <?php
        }
    ?>


        <div class="w-full  bg-white rounded-b-lg p-3 flex flex-wrap space-y-10">
            <div></div>
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl bg-white">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Trafico</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm py-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Total
                        </div>
                        <div class="w-4/12">
                            Con Intencion
                        </div>
                        <div class="w-2/12">
                            %
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-1 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_intencion'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['flujo']['flujo'])>0?100*$indicadores['flujo']['flujo_intencion']/$indicadores['flujo']['flujo']:0,0)}}%
                        </div>
                    </div>

                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_intencion_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['flujo']['flujo_q1'])>0?100*$indicadores['flujo']['flujo_intencion_q1']/$indicadores['flujo']['flujo_q1']:0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_intencion_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['flujo']['flujo_q2'])>0?100*$indicadores['flujo']['flujo_intencion_q2']/$indicadores['flujo']['flujo_q1']:0,0)}}%
                        </div>
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Activaciones</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm py-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Total
                        </div>
                        <div class="w-4/12">
                            Cuota
                        </div>
                        <div class="w-2/12">
                            %
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-1 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_total']+$indicadores['ventas']['act_sin_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['ac']+$indicadores['cuotas']['asi'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['ac']+$indicadores['cuotas']['asi'])>0?100*($indicadores['ventas']['act_con_total']+$indicadores['ventas']['act_sin_total'])/($indicadores['cuotas']['ac']+$indicadores['cuotas']['asi']):0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm text-yellow-700">
                        <div class="w-2/12 px-2">Tradicional</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['ac'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['ac'])>0?100*($indicadores['ventas']['act_con_total'])/($indicadores['cuotas']['ac']):0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['ac_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['ac_q1'])>0?100*$indicadores['ventas']['act_con_q1']/$indicadores['cuotas']['ac_q1']:0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['ac_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['ac_q2'])>0?100*$indicadores['ventas']['act_con_q2']/$indicadores['cuotas']['ac_q2']:0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm pb-1 text-yellow-700">
                        <div class="w-2/12 px-2">Propio</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_sin_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['asi'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['asi'])>0?100*($indicadores['ventas']['act_sin_total'])/($indicadores['cuotas']['asi']):0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_sin_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['as_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['as_q1'])>0?100*$indicadores['ventas']['act_sin_q1']/$indicadores['cuotas']['as_q1']:0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_sin_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['as_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['as_q2'])>0?100*$indicadores['ventas']['act_sin_q2']/$indicadores['cuotas']['as_q2']:0,0)}}%
                        </div>
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Renovaciones</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm py-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Total
                        </div>
                        <div class="w-4/12">
                            Cuota
                        </div>
                        <div class="w-2/12">
                            %
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-1 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['ren_con_total']+$indicadores['ventas']['ren_sin_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['rc']+$indicadores['cuotas']['rs'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['rc']+$indicadores['cuotas']['rs'])>0?100*($indicadores['ventas']['ren_con_total']+$indicadores['ventas']['ren_sin_total'])/($indicadores['cuotas']['rc']+$indicadores['cuotas']['rs']):0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm text-yellow-700">
                        <div class="w-2/12 px-2">Tradicional</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['ren_con_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['rc'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['rc'])>0?100*($indicadores['ventas']['ren_con_total'])/($indicadores['cuotas']['rc']):0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['ren_con_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['rc_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['rc_q1'])>0?100*$indicadores['ventas']['ren_con_q1']/$indicadores['cuotas']['rc_q1']:0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['ren_con_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['rc_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['rc_q2'])>0?100*$indicadores['ventas']['ren_con_q2']/$indicadores['cuotas']['rc_q2']:0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm pb-1 text-yellow-700">
                        <div class="w-2/12 px-2">Propio</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['ren_sin_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['rs'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['rs'])>0?100*($indicadores['ventas']['ren_sin_total'])/($indicadores['cuotas']['rs']):0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['ren_sin_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['rs_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['rs_q1'])>0?100*$indicadores['ventas']['ren_sin_q1']/$indicadores['cuotas']['rs_q1']:0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['ren_sin_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['rs_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['rs_q2'])>0?100*$indicadores['ventas']['ren_sin_q2']/$indicadores['cuotas']['rs_q2']:0,0)}}%
                        </div>
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Productividad</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm py-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Productividad (minutos)
                        </div>
                        <div class="w-4/12">
                            Objetivo
                        </div>
                        <div class="w-2/12">
                            %
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-1 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['productividad']['total']['minutos'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['minutos']-$indicadores['productividad']['total']['incidencias'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['minutos']-$indicadores['productividad']['total']['incidencias'])>0?100*$indicadores['productividad']['total']['minutos']/($indicadores['cuotas']['minutos']-$indicadores['productividad']['total']['incidencias']):0,0)}}%
                        </div>
                    </div>

                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['productividad']['q1']['minutos'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['minutos_q1']-$indicadores['productividad']['q1']['incidencias'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['minutos_q1']-$indicadores['productividad']['q1']['incidencias'])>0?100*$indicadores['productividad']['q1']['minutos']/($indicadores['cuotas']['minutos_q1']-$indicadores['productividad']['q1']['incidencias']):0,0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['productividad']['q2']['minutos'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['cuotas']['minutos_q2']-$indicadores['productividad']['q2']['incidencias'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{number_format(intval($indicadores['cuotas']['minutos_q2']-$indicadores['productividad']['q2']['incidencias'])>0?100*$indicadores['productividad']['q2']['minutos']/($indicadores['cuotas']['minutos_q2']-$indicadores['productividad']['q2']['incidencias']):0,0)}}%
                        </div>
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Ticket Promedio Activaciones</div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base py-1 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['act_ticket_total'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm text-yellow-700">
                        <div class="w-2/12 px-2">Tradicional</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['act_con_ticket_total'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['act_con_ticket_q1'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['act_con_ticket_q2'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm pb-1 text-yellow-700">
                        <div class="w-2/12 px-2">Propio</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['act_sin_ticket_total'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['act_sin_ticket_q1'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['act_sin_ticket_q2'],0)}}
                        </div>
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Ticket Promedio Renovaciones</div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base py-1 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['ren_ticket_total'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm text-yellow-700">
                        <div class="w-2/12 px-2">Tradicional</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['ren_con_ticket_total'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['ren_con_ticket_q1'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['ren_con_ticket_q2'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-sm pb-1 text-yellow-700">
                        <div class="w-2/12 px-2">Propio</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['ren_sin_ticket_total'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['ren_sin_ticket_q1'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-10/12">
                            ${{number_format($indicadores['ventas']['ren_sin_ticket_q2'],0)}}
                        </div>
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
            
            <div class="w-1/4 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Efectividad Tienda</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm pt-3 pb-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Trafico
                        </div>
                        <div class="w-4/12">
                            Solicitudes
                        </div>
                        <div class="w-2/12"></div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-3 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['solicitudes']['tda_total'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['flujo']['flujo'])>0?number_format(100*$indicadores['solicitudes']['tda_total']/$indicadores['flujo']['flujo'],0):0}}%
                        </div>
                    </div>

                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['solicitudes']['tda_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['flujo']['flujo_q1'])>0?number_format(100*$indicadores['solicitudes']['tda_q1']/$indicadores['flujo']['flujo_q1'],0):0}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['solicitudes']['tda_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['flujo']['flujo_q2'])>0?number_format(100*$indicadores['solicitudes']['tda_q2']/$indicadores['flujo']['flujo_q2'],0):0}}%
                        </div>
                    </div>

                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/4 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Productividad Tienda</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm pt-3 pb-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Flujo
                        </div>
                        <div class="w-4/12">
                            Ventas
                        </div>
                        <div class="w-2/12"></div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-3 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_tda_total']+$indicadores['ventas']['act_sin_tda_total']+$indicadores['ventas']['ren_con_tda_total']+$indicadores['ventas']['ren_sin_tda_total'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['flujo']['flujo'])>0?number_format(100*($indicadores['ventas']['act_con_tda_total']+$indicadores['ventas']['act_sin_tda_total']+$indicadores['ventas']['ren_con_tda_total']+$indicadores['ventas']['ren_sin_tda_total'])/$indicadores['flujo']['flujo'],0):0}}%
                        </div>
                    </div>

                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_tda_q1']+$indicadores['ventas']['act_sin_tda_q1']+$indicadores['ventas']['ren_con_tda_q1']+$indicadores['ventas']['ren_sin_tda_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['flujo']['flujo_q1'])>0?number_format(100*($indicadores['ventas']['act_con_tda_q1']+$indicadores['ventas']['act_sin_tda_q1']+$indicadores['ventas']['ren_con_tda_q1']+$indicadores['ventas']['ren_sin_tda_q1'])/$indicadores['flujo']['flujo_q1'],0):0}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['flujo']['flujo_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_tda_q2']+$indicadores['ventas']['act_sin_tda_q2']+$indicadores['ventas']['ren_con_tda_q2']+$indicadores['ventas']['ren_sin_tda_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['flujo']['flujo_q2'])>0?number_format(100*($indicadores['ventas']['act_con_tda_q2']+$indicadores['ventas']['act_sin_tda_q2']+$indicadores['ventas']['ren_con_tda_q2']+$indicadores['ventas']['ren_sin_tda_q2'])/$indicadores['flujo']['flujo_q2'],0):0}}%
                        </div>
                    </div>

                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/4 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Efectividad Generacion</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm pt-3 pb-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Contactos
                        </div>
                        <div class="w-4/12">
                            Solicitudes
                        </div>
                        <div class="w-2/12"></div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-3 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['demanda']['demanda_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['solicitudes']['dem_total'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['demanda']['demanda_total'])>0?number_format(100*$indicadores['solicitudes']['dem_total']/$indicadores['demanda']['demanda_total'],0):0}}%
                        </div>
                    </div>

                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['demanda']['demanda_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['solicitudes']['dem_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['demanda']['demanda_q1'])>0?number_format(100*$indicadores['solicitudes']['dem_q1']/$indicadores['demanda']['demanda_q1'],0):0}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['demanda']['demanda_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['solicitudes']['dem_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['demanda']['demanda_q2'])>0?number_format(100*$indicadores['solicitudes']['dem_q2']/$indicadores['demanda']['demanda_q2'],0):0}}%
                        </div>
                    </div>

                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/4 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Productividad Generacion</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm pt-3 pb-1">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            Contactos
                        </div>
                        <div class="w-4/12">
                            Ventas
                        </div>
                        <div class="w-2/12"></div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base pb-3 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-4/12">
                            {{number_format($indicadores['demanda']['demanda_total'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_dem_total']+$indicadores['ventas']['act_sin_dem_total']+$indicadores['ventas']['ren_con_dem_total']+$indicadores['ventas']['ren_sin_dem_total'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['demanda']['demanda_total'])>0?number_format(100*($indicadores['ventas']['act_con_dem_total']+$indicadores['ventas']['act_sin_dem_total']+$indicadores['ventas']['ren_con_dem_total']+$indicadores['ventas']['ren_sin_dem_total'])/$indicadores['demanda']['demanda_total'],0):0}}%
                        </div>
                    </div>

                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['demanda']['demanda_q1'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_dem_q1']+$indicadores['ventas']['act_sin_dem_q1']+$indicadores['ventas']['ren_con_dem_q1']+$indicadores['ventas']['ren_sin_dem_q1'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['demanda']['demanda_q1'])>0?number_format(100*($indicadores['ventas']['act_con_dem_q1']+$indicadores['ventas']['act_sin_dem_q1']+$indicadores['ventas']['ren_con_dem_q1']+$indicadores['ventas']['ren_sin_dem_q1'])/$indicadores['demanda']['demanda_q1'],0):0}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-4/12">
                            {{number_format($indicadores['demanda']['demanda_q2'],0)}}
                        </div>
                        <div class="w-4/12">
                            {{number_format($indicadores['ventas']['act_con_dem_q2']+$indicadores['ventas']['act_sin_dem_q2']+$indicadores['ventas']['ren_con_dem_q2']+$indicadores['ventas']['ren_sin_dem_q2'],0)}}
                        </div>
                        <div class="w-2/12">
                            {{intval($indicadores['demanda']['demanda_q2'])>0?number_format(100*($indicadores['ventas']['act_con_dem_q2']+$indicadores['ventas']['act_sin_dem_q2']+$indicadores['ventas']['ren_con_dem_q2']+$indicadores['ventas']['ren_sin_dem_q2'])/$indicadores['demanda']['demanda_q2'],0):0}}%
                        </div>
                    </div>

                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Pendientes Facturacion</div>
                    </div>
                    <div class="w-full flex flex-row text-center font-semibold text-sm py-1">
                        <div class="w-2/12"></div>
                        <div class="w-5/12">
                            Pendientes
                        </div>
                        <div class="w-5/12">
                            Monto Requerido (Prom)
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base py-3 font-bold text-blue-700">
                        <div class="w-2/12"></div>
                        <div class="w-5/12">
                            {{number_format($indicadores['pendientes']['pendientes_total'],0)}}
                        </div>
                        <div class="w-5/12">
                            ${{intval($indicadores['pendientes']['pendientes_total'])>0?number_format($indicadores['pendientes']['monto_total']/$indicadores['pendientes']['pendientes_total'],0):0}}
                        </div>
                    </div>

                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-2/12">
                            Q1
                        </div>
                        <div class="w-5/12">
                            {{number_format($indicadores['pendientes']['pendientes_q1'],0)}}
                        </div>
                        <div class="w-5/12">
                            ${{intval($indicadores['pendientes']['pendientes_q1'])>0?number_format($indicadores['pendientes']['monto_total_q1']/$indicadores['pendientes']['pendientes_q1'],0):0}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-2">
                        <div class="w-2/12">Q2</div>
                        <div class="w-5/12">
                            {{number_format($indicadores['pendientes']['pendientes_q2'],0)}}
                        </div>
                        <div class="w-5/12">
                            ${{intval($indicadores['pendientes']['pendientes_q2'])>0?number_format($indicadores['pendientes']['monto_total_q2']/$indicadores['pendientes']['pendientes_q2'],0):0}}
                        </div>
                    </div>

                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Cobertura Plantilla</div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base py-1 font-bold text-blue-700">
                        <div class="w-full text-3xl py-5">
                            {{$indicadores['cuotas']['ejecutivos']>0?number_format(100*$indicadores['activos']['activos']/$indicadores['cuotas']['ejecutivos'],0):0}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-1/2">
                            POSICIONES
                        </div>
                        <div>
                            {{number_format($indicadores['cuotas']['ejecutivos'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-5">
                        <div class="w-1/2">
                            ACTIVOS
                        </div>
                        <div>
                            {{number_format($indicadores['activos']['activos'],0)}}
                        </div>
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
            <div class="w-1/3 p-3"><!--DIV INDICADOR-->
                <div class="w-full flex flex-col border rounded-xl shadow-xl">
                    <div class="w-full flex flex-row text-center font-bold rounded-t-xl bg-gray-200 p-2">
                        <div class="w-full">Rentabilidad</div>
                    </div>
                    <div class="w-full flex flex-row text-center text-base py-1 font-bold text-blue-700">
                        <div class="w-full text-3xl py-5">
                            {{number_format($indicadores['rentabilidad']['rentabilidad'],0)}}%
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-1/2">
                            INGRESOS
                        </div>
                        <div>
                            ${{number_format($indicadores['rentabilidad']['ingresos'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-1/2">
                            GASTOS FIJOS
                        </div>
                        <div>
                            ${{number_format($indicadores['rentabilidad']['g_fijos'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-1/2">
                            GASTOS INDIRECTOS
                        </div>
                        <div>
                            ${{number_format($indicadores['rentabilidad']['g_indirectos'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center">
                        <div class="w-1/2">
                            COSTO VENTA
                        </div>
                        <div>
                            ${{number_format($indicadores['rentabilidad']['c_venta'],0)}}
                        </div>
                    </div>
                    <div class="w-full flex flex-row text-xs text-center pb-5 text-red-500">
                        @if($indicadores['rentabilidad']['consistencia']=="NO")
                        <div class="w-1/2">
                            NOTA:
                        </div>
                        <div>
                            ${{$indicadores['rentabilidad']['leyenda_gastos']}}
                        </div>
                        @endif
                    </div>
                </div>
            </div> <!--FIN DIV INDICADOR-->
        </div> <!--FIN CONTENIDO-->
    </div>    
</x-app-layout>
