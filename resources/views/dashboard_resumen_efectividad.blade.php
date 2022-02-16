<x-app-layout>
    <x-slot name="header">
            {{ __('Resumen efectividad') }}
    </x-slot>
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Resumen efectividad - {{$periodo}}</div>
            @if(Auth::user()->puesto!='Director')
            <div class="w-full text-2xl text-green-600 font-bold flex justify-end"><a href="/export_demanda/{{$periodo}}"><i class="far fa-file-excel"></i> <span class="font-normal text-xs text-gray-700">Generacion Demanda</a></a></div>
            @endif
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
        </div> <!--FIN ENCABEZADO-->
        <div class="pt-4 px-4 text-base font-semibold">{{$titulo}}</div>
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col">
                    <div class="w-full text-lg text-gray-700 font-bold flex justify-center pt-10">
                        Efectividad En Tienda
                    </div>
                    <div class="w-full text-7xl text-blue-700 font-bold flex justify-center p-10">
                        {{number_format($flujo>0?100*$e_tienda/$flujo:0,0)}}%
                    </div>
                    <div class="w-full text-gray-700 flex justify-center flex-col">
                        <div class="w-full flex flex-row space-x-4">
                            <div class="w-1/2 flex justify-end font-semibold">
                                Flujo Total
                            </div>
                            <div class="w-1/2">
                                {{number_format($flujo,0)}}
                            </div>
                        </div>
                        <div class="w-full flex flex-row space-x-4">
                            <div class="w-1/2 flex justify-end font-semibold">
                                Ordenes Facturadas
                            </div>
                            <div class="w-1/2">
                                {{number_format($e_tienda,0)}}
                            </div>
                        </div>

                    </div>
                </div>
                <div class="w-1/2 flex flex-col pb-5">
                    <div class="w-full text-lg text-gray-700 font-bold flex justify-center pt-10">
                        Efectividad Generacion Demanda
                    </div>
                    <div class="w-full text-7xl text-blue-700 font-bold flex justify-center p-10">
                        {{number_format(($llamada+$sms+$rs)>0?100*($e_llamada+$e_sms+$e_rs)/($llamada+$sms+$rs):0,2)}}%
                    </div>
                    <div class="w-full text-gray-700 flex justify-center flex-col shadow-lg rounded-b-lg">
                        <div class="w-full flex flex-row space-x-4 bg-gray-200 rounded-t-lg font-semibold border-b">
                            <div class="w-1/4 flex justify-end">
                                Tipo Contacto
                            </div>
                            <div class="w-1/4 flex justify-center">
                                #Contactos
                            </div>
                            <div class="w-1/4 flex justify-center">
                                Ordenes<br>Facturadas
                            </div>
                            <div class="w-1/4 flex justify-center">
                                Efectividad
                            </div>
                        </div>
                        <div class="w-full flex flex-row space-x-4">
                            <div class="w-1/4 flex justify-end">
                                Llamadas
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($llamada,0)}}
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($e_llamada,0)}}
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($llamada>0?100*$e_llamada/$llamada:0,2)}}%
                            </div>
                        </div>
                        <div class="w-full flex flex-row space-x-4">
                            <div class="w-1/4 flex justify-end">
                                SMS
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($sms,0)}}
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($e_sms,0)}}
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($sms>0?100*$e_sms/$sms:0,2)}}%
                            </div>
                        </div>
                        <div class="w-full flex flex-row space-x-4">
                            <div class="w-1/4 flex justify-end">
                                Redes Sociales
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($rs,0)}}
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($e_rs,0)}}
                            </div>
                            <div class="w-1/4 flex justify-center">
                                {{number_format($rs>0?100*$e_rs/$rs:0,2)}}%
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col">
                    <div class="w-full text-lg text-gray-700 font-bold flex justify-center">
                        Aportacion
                    </div>
                    <div class="w-full text-7xl text-blue-700 font-bold flex justify-center">
                        {{number_format(($e_tienda+$e_llamada+$e_sms+$e_rs)>0?100*$e_tienda/($e_tienda+$e_llamada+$e_sms+$e_rs):0,0)}}%
                    </div>
                </div>
                <div class="w-1/2 flex flex-col">
                    <div class="w-full text-lg text-gray-700 font-bold flex justify-center">
                        Aportacion
                    </div>
                    <div class="w-full text-7xl text-blue-700 font-bold flex justify-center">
                        {{number_format(($e_tienda+$e_llamada+$e_sms+$e_rs)>0?100*($e_llamada+$e_sms+$e_rs)/($e_tienda+$e_llamada+$e_sms+$e_rs):0,0)}}%
                    </div>
                </div>
            </div>
        </div>
        <?php
            if($origen=="G" || $origen=="R" ||$origen=="D")
            //if(false)
            {
        ?>
        <div class="w-full bg-gray-200 p-3 flex flex-col"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Detalles</div>
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full bg-white p-3 flex justify-center text-xs">
            <table class="">
                <tr>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3"></td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3"></td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Flujo</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Facturadas<br>Tienda</td>
                    <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-3">Efectividad<br>Tienda</td>
                    <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-3">Aportacion<br>Tienda</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Contactos<br>Generacion Demanda</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Facturadas<br>G Demanda</td>
                    <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-3">Efectividad<br>G Demanda</td>
                    <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-3">Aportacion<br>G Demanda</td>
                </tr>
                <?php
                $color=true;
                foreach($detalles as $detalle)
                {
                ?>
                <tr>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><a href="/dashboard_resumen_efectividad/{{$periodo}}/{{($origen=="G"?'E':($origen=="R"?"G":"R"))}}/{{$detalle->llave}}/{{$detalle->value}}">{{$detalle->llave}}</a></td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3">{{$detalle->value}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->flujo,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->facturadas_tda,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->flujo>0?100*$detalle->facturadas_tda/$detalle->flujo:0,0)}}%</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format(($detalle->facturadas_tda+$detalle->facturadas_dem)>0?100*$detalle->facturadas_tda/($detalle->facturadas_tda+$detalle->facturadas_dem):0,0)}}%</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->demanda,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->facturadas_dem)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->demanda>0?100*$detalle->facturadas_dem/$detalle->demanda:0,0)}}%</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format(($detalle->facturadas_tda+$detalle->facturadas_dem)>0?100*$detalle->facturadas_dem/($detalle->facturadas_tda+$detalle->facturadas_dem):0,0)}}%</td>
                </tr>
                <?php
                    $color=!$color;
                }
                ?>
            </table>
        </div>
        <?php
            }
        ?>
    </div>    
</x-app-layout>
