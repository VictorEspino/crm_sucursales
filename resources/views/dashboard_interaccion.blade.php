<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Interaccion') }}
    </x-slot>
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Indicadores de Interaccion - {{$periodo}}</div>            
            <div class="w-full text-2xl text-green-600 font-bold flex justify-end"><a href="/export_interaccion/{{$periodo}}"><i class="far fa-file-excel"></i></a></div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
        </div>
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col"> 
                    <div class="w-full px-20">
                        <canvas id="myChart"></canvas>
                    </div>
                    <div class="w-full text-xl flex justify-center font-bold pt-6">
                        <span class=""></span>
                    </div>
                </div> 
                <div class="w-1/2 flex flex-col p-3">
                    <div class="w-full flex justify-center">
                        <table class="text-sm">

                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm">Con intencion de compra</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($ci,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($pi,0)}}%</td>                                
                            </tr>
                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm">Sin intencion de compra</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($si,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 text-sm"><center>{{number_format($ps,0)}}%</td>                                
                            </tr>
                            <tr class="">
                                <td class="border border-gray-400 text-gray-700 font-light bg-gray-200 px-3 text-sm">Total</td>
                                <td class="border border-gray-400 text-gray-700 font-light bg-gray-200 px-3 text-sm"><center>{{number_format($total,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light bg-gray-200 px-3 text-sm"><center>100%</td>                                
                            </tr>

                        </table>
                    </div>
                    <div class="w-full text-xl flex justify-center font-bold pt-6">
                        <span class=""></span>
                    </div>
                </div> 
                
            </div>

        </div>
        
        <div class="w-full bg-gray-200 p-3 flex flex-col">
            <div class="w-full text-lg font-semibold">Con Intencion de Compra</div>
        </div>
    
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col">
                    <div class="w-10/12 px-16">
                        <canvas id="myChart2"></canvas>
                    </div>
                    <div class="w-full text-xs flex justify-center font-light pt-6">
                        <table>
                            <?php
                            $total_tabla=0;
                            foreach($tramites_con as $tramite)
                            {
                                $total_tabla=$total_tabla+$tramite->visitas;
                            }
                            foreach($tramites_con as $tramite)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$tramite->tramite}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($tramite->visitas,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$tramite->visitas/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w-1/2 flex flex-col">
                    <div class="w-10/12 px-16">
                        <canvas id="myChart3"></canvas>
                    </div>
                    <div class="w-full text-xs flex justify-center font-light pt-6">
                    <table>
                            <?php
                            $total_tabla=0;
                            foreach($fin_con as $tramite)
                            {
                                $total_tabla=$total_tabla+$tramite->visitas;
                            }
                            foreach($fin_con as $tramite)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$tramite->fin_interaccion}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($tramite->visitas,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$tramite->visitas/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="w-full bg-gray-200 p-3 flex flex-col">
            <div class="w-full text-lg font-semibold">Sin Intencion de Compra</div>
        </div>
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col">
                <div class="w-10/12 px-16">
                        <canvas id="myChart4"></canvas>
                    </div>
                    <div class="w-full text-xs flex justify-center font-light pt-6">
                        <table>
                            <?php
                            $total_tabla=0;
                            foreach($tramites_sin as $tramite)
                            {
                                $total_tabla=$total_tabla+$tramite->visitas;
                            }
                            foreach($tramites_sin as $tramite)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$tramite->tramite}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($tramite->visitas,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$tramite->visitas/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="w-1/2 flex flex-col">
                <div class="w-10/12 px-16">
                        <canvas id="myChart5"></canvas>
                    </div>
                    <div class="w-full text-xs flex justify-center font-light pt-6">
                    <table>
                            <?php
                            $total_tabla=0;
                            foreach($fin_sin as $tramite)
                            {
                                $total_tabla=$total_tabla+$tramite->visitas;
                            }
                            foreach($fin_sin as $tramite)
                            {
                            ?>                            
                            <tr>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 ">{{$tramite->fin_interaccion}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($tramite->visitas,0)}}</td>
                                <td class="border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format(100*$tramite->visitas/$total_tabla,0)}}%</td>
                            </tr>
                            <?php
                            }
                            ?>
                            <tr>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 ">Total</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>{{number_format($total_tabla,0)}}</td>
                                <td class="bg-gray-200 border border-gray-400 text-gray-700 font-light px-3 "><center>100%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php
            if($origen=="G" || $origen=="R")
            {
        ?>
        <div class="w-full bg-gray-200 p-3 flex flex-col">
            <div class="w-full text-lg font-semibold">Detalles</div>
        </div>
        <div class="w-full bg-white p-3 flex justify-center text-xs">
            <table class="">
                <tr>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3"></td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3"></td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Sin Intencion</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Con Intencion</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Total</td>
                    <td class="bg-green-500 border border-gray-400 text-gray-200 font-semibold px-3">%intencion</td>
                </tr>
                <?php
                $color=true;
                foreach($details as $detalle)
                {
                ?>
                <tr>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><a href="/dashboard_interaccion/{{$periodo}}/{{$origen=='G'?'E':'G'}}/{{$detalle->llave}}/{{$detalle->value}}">{{$detalle->llave}}</a></td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3">{{$detalle->value}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->sin_intencion,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->intencion,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->total,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format(100*$detalle->intencion/$detalle->total,0)}}%</td>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.js"></script>
        
<script>
var ctx = document.getElementById('myChart').getContext('2d');
var ctx2 = document.getElementById('myChart2').getContext('2d');
var ctx3 = document.getElementById('myChart3').getContext('2d');
var ctx4 = document.getElementById('myChart4').getContext('2d');
var ctx5 = document.getElementById('myChart5').getContext('2d');

var myChart = new Chart(ctx, {
    type: 'pie',
    data: {
        labels: ['Con intencion de compra', 'Sin intencion de compra'],
        datasets: [{
            label: '# of Votes',
            data: [{{$ci}},{{$si}}],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
            plugins: {
                title: {
                    display: true,
                    text: '{{$titulo}}'
                }
            }
        }
   
});
var myChart2 = new Chart(ctx2, {
    type: 'pie',
    data: {
        labels: [
            <?php
                foreach($tramites_con as $tramite)
                {
            ?>
                    '{{$tramite->tramite}}',
            <?php
                }
            ?>
                ],
        datasets: [{
            label: '# of Votes',
            data: [
                <?php
                foreach($tramites_con as $tramite)
                {
            ?>
                    '{{$tramite->visitas}}',
            <?php
                }
            ?>
                
                ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Tramites con Intencion'
                },
                legend:{
                    display: false,
                }
            }
        }
   
});
var myChart3 = new Chart(ctx3, {
    type: 'pie',
    data: {
        labels: [
            <?php
                foreach($fin_con as $tramite)
                {
            ?>
                    '{{$tramite->fin_interaccion}}',
            <?php
                }
            ?>
                ],
        datasets: [{
            label: '# of Votes',
            data: [
                <?php
                foreach($fin_con as $tramite)
                {
            ?>
                    '{{$tramite->visitas}}',
            <?php
                }
            ?>
                
                ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Resultado de Tramites'
                },
                legend:{
                    display: false,
                }
            }
        }
   
});
var myChart4 = new Chart(ctx4, {
    type: 'pie',
    data: {
        labels: [
            <?php
                foreach($tramites_sin as $tramite)
                {
            ?>
                    '{{$tramite->tramite}}',
            <?php
                }
            ?>
                ],
        datasets: [{
            label: '# of Votes',
            data: [
                <?php
                foreach($tramites_sin as $tramite)
                {
            ?>
                    '{{$tramite->visitas}}',
            <?php
                }
            ?>
                
                ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Tramites sin Intencion'
                },
                legend:{
                    display: false,
                }
            }
        }
   
});
var myChart5 = new Chart(ctx5, {
    type: 'pie',
    data: {
        labels: [
            <?php
                foreach($fin_sin as $tramite)
                {
            ?>
                    '{{$tramite->fin_interaccion}}',
            <?php
                }
            ?>
                ],
        datasets: [{
            label: '# of Votes',
            data: [
                <?php
                foreach($fin_sin as $tramite)
                {
            ?>
                    '{{$tramite->visitas}}',
            <?php
                }
            ?>
                
                ],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
            plugins: {
                title: {
                    display: true,
                    text: 'Resultado de Tramites'
                },
                legend:{
                    display: false,
                }
            }
        }
   
});

</script>
</x-app-layout>
