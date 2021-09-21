<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Efectividad') }}
    </x-slot>
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Indicadores de Efectividad - {{$periodo}}</div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col"> <!--DIV GRAFICO-->
                    <div class="w-full px-5">
                        <canvas id="chart-area" height="250"></canvas>
                    </div>
                    <div class="w-full text-xl flex justify-center font-bold pt-6">
                        <span class="">{{number_format($facturadas,0)}} Ordenes Facturadas</span>
                    </div>
                </div> <!--FIN DIV GRAFICO-->
                <div class="w-1/2 flex flex-col p-3"> <!--DIV MEDICIONES-->
                    <div class="w-full flex justify-center">
                        <table class="text-sm">
                            <tr class="">
                                <td class="" colspan=3></td>
                                <td class="border border-gray-400 bg-green-700 text-gray-200 font-semibold px-3"><center>Cuota</td>
                                <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3"><center>Alcance</td>
                            </tr>
                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm" rowspan=2><center>Activaciones</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm">Con Equipo</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($ac,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($c_ac,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($a_ac,0)}}%</td>
                            </tr>
                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm">Sin Equipo</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($as,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($c_as,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($a_as,0)}}%</td>
                            </tr>
                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm" rowspan=2>Renovaciones</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm">Con Equipo</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($rc,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($c_rc,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($a_rc,0)}}%</td>
                            </tr>
                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm">Sin Equipo</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($rs,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($c_rs,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($a_rs,0)}}%</td>
                            </tr>
                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-bold px-3 text-sm" colspan=2><center>Total Conversion</td>
                                <td class="border border-gray-400 text-gray-700 font-bold px-3 text-sm"><center>{{number_format($total,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm" colspan=2></td>
                            </tr>

                        </table>
                    </div>
                    <div class="text-xl flex justify-center font-semibold pt-12">
                        EFECTIVIDAD vs. TOTAL VISITAS = {{number_format($ev,0)}}%
                    </div>
                    <div class="text-xl flex justify-center font-semibold pt-12">
                        EFECTIVIDAD vs. INTENCION DE COMPRA = {{number_format($ei,0)}}%
                    </div>
                </div> <!--FIN DIV MEDICIONES-->
                
            </div>
        <?php 
                $color=true;
                if($origen=="R" || $origen=="G")
                {
        ?>
            <div class="w-full flex justify-center pt-4 text-sm">
                <table class="">
                    <tr class="">
                        <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3"></td>
                        <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3"></td>
                        <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3">Visitas</td>
                        <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3">Con Intecion</td>
                        <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3">Facturadas</td>
                        <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3">Efectividad Visitas</td>
                        <td class="border border-gray-400 bg-blue-700 text-gray-200 font-semibold px-3">Efectividad Intencion</td>
                    </tr>
        <?php
                foreach($details as $detalle)
                {
                    $ev_detalle=0;
                    $ei_detalle=0;
                    $liga='/dashboard_efectividad/'.$periodo.'/'.($origen=="G"?'E':'G').'/'.$detalle->llave."/".$detalle->value;
                    try{
                        $ev_detalle=100*$detalle->facturadas/$detalle->visitas;
                    }
                    catch(Exception $e)
                    {
                        $ev_detalle=0;
                    }
                    try{
                        $ei_detalle=100*$detalle->facturadas/$detalle->intencion;
                    }
                    catch(Exception $e)
                    {
                        $ei_detalle=0;
                    }
        ?>                    
                    <tr class="">
                        <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm {{$color?'bg-gray-200':''}}"><a href="{{$liga}}">{{$detalle->llave}}</a></td>
                        <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm {{$color?'bg-gray-200':''}}">{{$detalle->value}}</td>
                        <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm {{$color?'bg-gray-200':''}}"><center>{{$detalle->visitas}}</td>
                        <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm {{$color?'bg-gray-200':''}}"><center>{{$detalle->intencion}}</td>
                        <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm {{$color?'bg-gray-200':''}}"><center>{{$detalle->facturadas}}</td>
                        <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm {{$color?'bg-gray-200':''}}"><center>{{number_format($ev_detalle,0)}}%</td>
                        <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm {{$color?'bg-gray-200':''}}"><center>{{number_format($ei_detalle,0)}}%</td>
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
    </div>
    
<script src="{{ asset('js/chart.funnel.bundled.js') }}"></script>
<script>
    var config = {
        type: 'funnel',
        data: {
            datasets: [{
                data: [{{$visitas}}, {{$intencion}}, {{$solicitudes}},{{$aprobadas}},{{$facturadas}}],
                backgroundColor: [
                    "#FDAF75",
                    "#F8C471",
                    "#F9E79F",
                    "#ABEBC6",
                    "#85C1E9"
                ],
                hoverBackgroundColor: [
                    "#FF8A33",
                    "#F5B041",
                    "#F4D03F",
                    "#58D68D",
                    "#3498DB"
                ]
            }],
            labels: [
                "Visitas",
                "Con Intencion de Compra",
                "Ordenes Generadas",
                "Ordenes Aprobadas",
                "Ordenes Facturadas"
            ]
        },
        options: {
            responsive: true,
            sort: 'desc',
            legend: {
                position: 'top'
            },
            title: {
                display: true,
                text: '{{$titulo}}'
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    };

    window.onload = function() {
        var ctx = document.getElementById("chart-area").getContext("2d");
        window.myDoughnut = new Chart(ctx, config);
    };
</script>
</x-app-layout>
