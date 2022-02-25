<x-app-layout>
    <x-slot name="header">
            {{ __('Rentabilidad') }}
    </x-slot>
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Rentabilidad : {{$titulo_principal}} - {{$periodo}}</div>
            @if(Auth::user()->puesto=='Director')
            <div class="w-full text-2xl text-green-600 font-bold flex justify-end"><a href="/export_rentabilidad/{{$periodo}}"><i class="far fa-file-excel"></i></a></div>
            @endif
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
            
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full bg-white flex flex-col pb-8">
            @if(!$consistencia)
            <div class="w-full bg-red-400 text-white p-3 font-bold"><center>Alert: Actualizacion de Gastos - evaluacion de rentabilidad con gastos del periodo "{{$titulo_gastos}}"</center></div>
            @endif

            <div class="w-full flex flex-row space-x-2 px-3 pt-8 pb-3">
                <div class="w-2/6 px-4 text-5xl flex text-center items-center font-bold">
                    {{$titulo_principal}} 
                </div>
                <div class="w-1/6 text-white px-3 pt-3 flex flex-col rounded-lg shadow-xl bg-gradient-to-br from-red-700 to-red-300">
                    <div class="text-sm font-semibold">Total Gastos</div>
                    <div class="px-4 pt-5 pb-5 text-2xl font-semibold flex justify-center">
                        ${{number_format($sum_gastos,0)}}
                    </div>
                </div>
                <div class="w-1/6 text-gray-700 px-3 pt-3 flex flex-col rounded-lg shadow-xl bg-gradient-to-br from-yellow-300 to-red-100">
                    <div class="text-sm font-semibold">Gastos Fijos</div>
                    <div class="px-4 pt-5 pb-5 text-2xl font-semibold flex justify-center">
                        ${{number_format($gastos_fijos,0)}}
                    </div>
                </div>
                <div class="w-1/6 text-gray-700 px-3 pt-3 flex flex-col rounded-lg shadow-xl bg-gradient-to-br from-yellow-300 to-red-100">
                    <div class="text-sm font-semibold">Gastos Indirectos</div>
                    <div class="px-4 pt-5 pb-5 text-2xl font-semibold flex justify-center">
                        ${{number_format($gastos_indirectos,0)}}
                    </div>
                </div>
                <div class="w-1/6 text-gray-700 px-3 pt-3 flex flex-col rounded-lg shadow-xl bg-gradient-to-br from-yellow-300 to-red-100">
                    <div class="text-sm font-semibold">Costos Venta</div>
                    <div class="px-4 pt-5 pb-5 text-2xl font-semibold flex justify-center">
                        ${{number_format($costos_venta,0)}}
                    </div>
                </div>                
            </div>
            <div class="w-full flex flex-row space-x-2 px-3 pt-3 pb-3">
                <div class="w-2/6 px-4 flex items-center text-gray-700 px-3 flex flex-col">
                    <div class="px-4 text-8xl font-bold flex justify-center">
                        {{number_format($porc_rentabilidad,0)}}%
                    </div>
                </div>
                <div class="w-1/6 text-white px-3 pt-3 flex flex-col rounded-lg shadow-xl bg-gradient-to-br from-green-900 to-green-400">
                    <div class="text-sm font-semibold">Ingresos</div>
                    <div class="px-4 pt-5 pb-5 text-2xl font-semibold flex justify-center">
                        ${{number_format($ingresos,0)}}
                    </div>
                </div>
                <div class="w-1/6">
                </div>  
                <div class="w-1/6">
                </div> 
                <div class="w-1/6">
                </div>    
            </div>
        </div>
        <div class="w-full bg-gray-200 flex flex-col p-3 font-bold text-base">
            Composicion de Ingresos
        </div>
        <div class="w-full rounded-b-lg bg-white flex flex-row p-3">
            <div class="w-1/5 flex justify-center font-bold flex flex-col"><div><span>Brackets</span></div><div><span class="text-xs font-normal">Basado en renta ERP</span></div></div>
            <div class="w-1/5 flex justify-center font-bold"><span id="activaciones"></span></div>
            <div class="w-1/5 flex justify-center font-bold"><span id="aep"></span></div>
            <div class="w-1/5 flex justify-center font-bold"><span id="renovaciones"></span></div>
            <div class="w-1/5 flex justify-center font-bold"><span id="rep"></span></div>
        </div>
        <div class="w-full rounded-b-lg bg-white flex flex-row p-3">
            <div class="w-1/5 flex justify-center">
                <table class="text-xs">
                    <tr>
                        <td class="px-1 bg-gray-200">Bracket</td>
                        <td class="px-1 bg-gray-200">De:</td>
                        <td class="px-1 bg-gray-200">Hasta:</td>
                    </tr> 
                    @foreach ($activaciones as $bracket)
                    <tr>
                        <td class="px-1 bg-white">{{$bracket->bracket}}</td>
                        <td class="px-1 bg-white">${{number_format($bracket_desde[$bracket->bracket],0)}}</td>
                        <td class="px-1 bg-white">${{number_format($bracket_hasta[$bracket->bracket],0)}}</td>
                    </tr> 
                    @endforeach   
                    
                </table>
            </div>
            <div class="w-1/5 flex justify-center font-bold"><canvas width="200" height="390" id="g_activaciones"></canvas></div>
            <div class="w-1/5 flex justify-center font-bold"><canvas width="200" height="390" id="g_aep"></canvas></div>
            <div class="w-1/5 flex justify-center font-bold"><canvas width="200" height="390" id="g_renovaciones"></canvas></div>
            <div class="w-1/5 flex justify-center font-bold"><canvas width="200" height="390" id="g_rep"></canvas></div>
        </div>
        @if($origen=="D" || $origen=="R")
        <div class="w-full bg-gray-200 flex flex-col p-3 font-bold text-base">
            Detalles
        </div>
        <div class="w-full bg-white flex flex-col p-3 text-lg">
            <div class="w-full flex justify-center">
                <table class="text-sm">
                    <tr>
                        <td class="px-1 bg-blue-500 font-semibold text-white px-3"></td>
                        <td class="px-1 bg-blue-500 font-semibold text-white px-3"></td>
                        <td class="px-1 bg-blue-500 font-semibold text-white px-3">Gastos<br>Fijos</td>
                        <td class="px-1 bg-blue-500 font-semibold text-white px-3">Gastos<br>Indirectos</td>
                        <td class="px-1 bg-blue-500 font-semibold text-white px-3">Costo<br>Venta</td>
                        <td class="px-1 bg-red-500 font-semibold text-white px-3">Total Gastos</td>
                        <td class="px-1 bg-green-500 font-semibold text-white px-3">Ingresos</td>
                        <td class="px-1 bg-blue-500 font-semibold text-white px-3">Rentabilidad</td>
                    </tr> 
                    @php
                     $color=true;   
                    @endphp
                    @foreach ($detalles as $detalle)
                    
                    <tr>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}"><a href="{{'/dashboard_rentabilidad/'.$periodo.'/'.($origen=="G"?'E':($origen=="R"?"G":"R")).'/'.$detalle->llave."/".$detalle->llave}}">{{$detalle->llave}}</a></td>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}">{{$origen=="D"?$detalle->llave:$sucursales[$detalle->llave]}}</td>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}"><center>${{number_format($detalle->gastos_fijos,0)}}</td>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}"><center>${{number_format($detalle->gastos_indirectos,0)}}</td>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}"><center>${{number_format($detalle->c_v,0)}}</td>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}"><center>${{number_format($detalle->c_v+$detalle->gastos_indirectos+$detalle->gastos_fijos,0)}}</td>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}"><center>${{number_format($detalle->ingresos,0)}}</td>
                        <td class="px-3 border {{$color?'bg-white':'bg-gray-100'}}"><center>{{number_format($detalle->rentabilidad,0)}}%</td>
                    </tr> 
                        @php
                        $color=!$color;   
                        @endphp
                    @endforeach   
                    
                </table>
            </div>
        </div>
        @endif
    </div>
    
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.js"></script>
<script>
var ctx1 = document.getElementById('g_activaciones').getContext('2d');
var ctx2 = document.getElementById('g_aep').getContext('2d');
var ctx3 = document.getElementById('g_renovaciones').getContext('2d');
var ctx4 = document.getElementById('g_rep').getContext('2d');
var myChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
        labels: [
            @foreach($activaciones as $registro)
            '{{$registro->bracket}}',
            @endforeach
        ],
        datasets: [{
            label: 'Activaciones',
            data: [
            @php $x=0; $ingreso=0;@endphp
            @foreach($activaciones as $registro)
            {{$registro->n}},
            @php $x=$x+$registro->n; $ingreso=$ingreso+$registro->ingreso; @endphp
            @endforeach
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        }
        ]
    },
    options: {indexAxis: 'y',plugins: {title: {display: false,text: 'Activaciones'},},
        responsive: true,scales: {x: {stacked: true,},y: {stacked: true}}
    }   
});
document.getElementById('activaciones').innerHTML = "ACTIVACIONES = {{number_format($x,0)}}<br>INGRESO = ${{number_format($ingreso,0)}}";
var myChart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: [
            @foreach($aep as $registro)
            '{{$registro->bracket}}',
            @endforeach
        ],
        datasets: [{
            label: 'AEP',
            data: [
            @php $x=0; $ingreso=0;@endphp
            @foreach($aep as $registro)
            {{$registro->n}},
            @php $x=$x+$registro->n; $ingreso=$ingreso+$registro->ingreso; @endphp
            @endforeach
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        }
        ]
    },
    options: {indexAxis: 'y',plugins: {title: {display: false,text: 'AEP'},},
        responsive: true,scales: {x: {stacked: true,},y: {stacked: true}}
    }   
});
document.getElementById('aep').innerHTML = "AEP = {{number_format($x,0)}}<br>INGRESO = ${{number_format($ingreso,0)}}";
var myChart3 = new Chart(ctx3, {
    type: 'bar',
    data: {
        labels: [
            @foreach($renovaciones as $registro)
            '{{$registro->bracket}}',
            @endforeach
        ],
        datasets: [{
            label: 'Renovaciones',
            data: [
            @php $x=0; $ingreso=0;@endphp
            @foreach($renovaciones as $registro)
            {{$registro->n}},
            @php $x=$x+$registro->n; $ingreso=$ingreso+$registro->ingreso; @endphp
            @endforeach
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        }
        ]
    },
    options: {indexAxis: 'y',plugins: {title: {display: false,text: 'Renovaciones'},},
        responsive: true,scales: {x: {stacked: true,},y: {stacked: true}}
    }   
});
document.getElementById('renovaciones').innerHTML = "RENOVACIONES = {{number_format($x,0)}}<br>INGRESO = ${{number_format($ingreso,0)}}";
var myChart4 = new Chart(ctx4, {
    type: 'bar',
    data: {
        labels: [
            @foreach($rep as $registro)
            '{{$registro->bracket}}',
            @endforeach
        ],
        datasets: [{
            label: 'REP',
            data: [
            @php $x=0; $ingreso=0;@endphp
            @foreach($rep as $registro)
            {{$registro->n}},
            @php $x=$x+$registro->n; $ingreso=$ingreso+$registro->ingreso; @endphp
            @endforeach
            ],
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 1
        }
        ]
    },
    options: {indexAxis: 'y',plugins: {title: {display: false,text: 'REP'},},
        responsive: true,scales: {x: {stacked: true,},y: {stacked: true}}
    }   
});
document.getElementById('rep').innerHTML = "REP = {{number_format($x,0)}}<br>INGRESO = ${{number_format($ingreso,0)}}";

</script>
</x-app-layout>
