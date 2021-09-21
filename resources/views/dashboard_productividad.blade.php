<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Productividad') }}
    </x-slot>
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Indicadores de Productividad - {{$periodo}}</div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col">
            <div class="w-full flex flex-row">            
                <div class="w-2/3">
                    <canvas id="myChart"  height="200"></canvas>
                </div>
                <div class="w-1/3 flex flex-col text-xl font-semibold">
                    <div class="w-full py-5 flex justify-center">
                        <span class="">Minutos Objetivo Acumulados {{number_format($minutos_objetivo_acum-$minutos_incidencias_acum,0)}}</span>
                    </div>
                    <div class="w-full py-5 flex justify-center">
                        <span class="">Minutos Productivos Acumulados {{number_format($minutos_productivos_acum,0)}}</span>
                    </div>
                    <div class="w-full py-5 flex justify-center">
                        <span class="">Dias transcurridos {{number_format($dias_transcurridos,0)}}</span>
                    </div>
                    
                </div>
                
            </div>

        </div>
        <?php
            if($origen=="G" || $origen=="R")
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
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Interaccion</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Funnel</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Ordenes</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Demanda</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Ejecutivos</td>                    
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Minutos Productivos</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Incidencias</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Minutos Objetivo</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Cumplimiento</td>
                </tr>
                <?php
                $color=true;
                foreach($details as $detalle)
                {
                ?>
                <tr>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><a href="/dashboard_productividad/{{$periodo}}/{{$origen=='G'?'E':'G'}}/{{$detalle->llave}}/{{$detalle->value}}">{{$detalle->llave}}</a></td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3">{{$detalle->value}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->interaccion,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->funnel,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->ordenes,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->demanda,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->ejecutivos,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format(($detalle->interaccion+$detalle->funnel+$detalle->ordenes+$detalle->demanda),0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format($detalle->dias_incidencias,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{number_format(($dias_transcurridos*$detalle->ejecutivos*$minutos_sucursal)-$detalle->incidencias,0)}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>
                    <?php
                    try{
                    ?>    
                        {{number_format(100*($detalle->interaccion+$detalle->funnel+$detalle->ordenes+$detalle->demanda)/(($dias_transcurridos*$detalle->ejecutivos*$minutos_sucursal)-$detalle->incidencias),0)}}
                    <?php
                    }
                    catch(\Exception $e)
                    {
                        echo 0;
                    }
                    ?>
                    %</td>
                    
                    
                </tr>
                <?php
                    $color=!$color;
                }
                ?>
            </table>
        </div>
        <?php
            }
            if($origen=="E")
            {
        ?>
        <div class="w-full bg-gray-200 p-3 flex flex-col"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Incidencias</div>
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full bg-white p-3 flex justify-center text-xs">
            <table class="">
                <tr>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Dia Incidencia</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Tipo</td>
                    <td class="bg-blue-500 border border-gray-400 text-gray-200 font-semibold px-3">Observaciones</td>                    
                </tr>
                <?php
                $color=true;
                foreach($reg_incidencias as $detalle)
                {
                ?>
                <tr>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{$detalle->dia_incidencia}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{$detalle->tipo}}</td>
                    <td class="{{$color?'bg-gray-200':''}} border border-gray-400 text-gray-700 font-light px-3"><center>{{$detalle->observaciones}}</td>
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

var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: [
            <?php
            foreach($tiempos as $tiempo)
            {
            ?>
                '{{$tiempo->dia}}',
            <?php
            }
            ?>
        ],
        datasets: [
            {
                label: 'Interacciones',
                data: [
            <?php
            foreach($tiempos as $tiempo)
            {
            ?>
                '{{$tiempo->interaccion}}',
            <?php
            }
            ?>                    
                    ],
                backgroundColor: [
                    'rgba(25, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(25, 99, 132, 1)',
                ],
            borderWidth: 1
            },
            {
                label: 'Seguimiento Prospectos',
                data: [
            <?php
                    foreach($tiempos as $tiempo)
            {
            ?>
                '{{$tiempo->funnel}}',
            <?php
            }
            ?>  
                    ],
                backgroundColor: [
                    'rgba(55, 190, 192, 0.2)',
                ],
                borderColor: [
                    'rgba(55, 190, 192, 1)',
                ],
            borderWidth: 1
            },
            {
                label: 'Ventas',
                data: [
                    <?php
                    foreach($tiempos as $tiempo)
            {
            ?>
                '{{$tiempo->ordenes}}',
            <?php
            }
            ?>          
                ],
                backgroundColor: [
                    'rgba(85, 99, 232, 0.2)',
                ],
                borderColor: [
                    'rgba(85, 99, 232, 1)',
                ],
            borderWidth: 1
            },
            {
                label: 'Generacion Demanda',
                data: [
                    <?php
                    foreach($tiempos as $tiempo)
            {
            ?>
                '{{$tiempo->demanda}}',
            <?php
            }
            ?>                      
                    ],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                ],
            borderWidth: 1
            },
            {
                label: 'Objetivo Diario',
                data: [
            <?php
            foreach($tiempos as $tiempo)
            {
            ?>
                '{{$minutos_objetivo-$tiempo->incidencias}}',
            <?php
            }
            ?>                    
                    ],
                backgroundColor: [
                    'rgba(25, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(25, 99, 132, 1)',
                ],
            borderWidth: 1,
            type: 'line',
              order: 0

            },
        ]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: '{{$titulo}}'
            },
        },
        responsive: true,
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true
          }
        }
    }
   
});
</script>
</x-app-layout>
