<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Ejecutivo Sucursales') }}
    </x-slot>
    <div class="flex flex-col w-full text-gray-700 shadow-lg rounded-lg px-3">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Medicion ejecutiva - {{$periodo}}</div>
            <div class="w-full text-lg font-semibold">{{$titulo}}</div>
            <div class="w-full text-lg font-semibold text-blue-900"><a href="/comentarios" target="_blank">Notas de seguimiento</a></div>
        @if($nav_origen=='DRILLDOWN')
            <div class="w-full text-sm text-red-700 font-bold"><a href="javascript: window.history.back()"><< Regresar</a></div>
        @endif
            <div class="w-full text-sm font-semibold text-red-400">Ultimo dia de información : {{$ultimo_dia}}</div>
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full  bg-white rounded-b-lg flex flex-wrap space-y-10">
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700 flex">Activaciones</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones</span></div>
                        <div class="w-full">
                        <canvas id="chartActivaciones" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td class="py-2 px-3"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-3"><center>{{$año}}</td>
                                <td class="py-2 px-3 rounded-tr-xl"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                            @endphp
                            @foreach($datos_año_anterior_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$datos_año_array[$index]['act']+$datos_año_array[$index]['aep'];
                                $ytd_ant=$ytd_ant+$historico['act']+$historico['aep'];
                                $ytd_act=$ytd_act+$dato_mes;
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($historico['act']+$historico['aep'],0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{($historico['act']+$historico['aep']>0)?number_format(100*$dato_mes/($historico['act']+$historico['aep'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-5 border">YTD</td>
                                <td class="text-xs px-3 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4 flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones CON equipo</span></div>
                        <div class="w-full">
                        <canvas id="chartActivacionesCE" width="200" height="300"></canvas>
                        </div>
                </div>
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones SIN equipo</span></div>
                        <div class="w-full">
                        <canvas id="chartActivacionesSE" width="200" height="300"></canvas>
                        </div>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Renovaciones</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones</span></div>
                        <div class="w-full">
                        <canvas id="chartRenovaciones" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td class="py-2 px-3"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-3"><center>{{$año}}</td>
                                <td class="py-2 px-3 rounded-tr-xl"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                            @endphp
                            @foreach($datos_año_anterior_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$datos_año_array[$index]['ren']+$datos_año_array[$index]['rep'];
                                $ytd_ant=$ytd_ant+$historico['ren']+$historico['rep'];
                                $ytd_act=$ytd_act+$dato_mes;
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($historico['ren']+$historico['rep'],0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{($historico['ren']+$historico['rep']>0)?number_format(100*$dato_mes/($historico['ren']+$historico['rep'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-5 border">YTD</td>
                                <td class="text-xs px-3 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4 flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones CON equipo</span></div>
                        <div class="w-full">
                        <canvas id="chartRenovacionesCE" width="200" height="300"></canvas>
                        </div>
                </div>
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones SIN equipo</span></div>
                        <div class="w-full">
                        <canvas id="chartRenovacionesSE" width="200" height="300"></canvas>
                        </div>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Rentabilidad</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Rentabilidad</span></div>
                        <div class="w-full">
                        <canvas id="chartRentabilidad" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td class="py-2 px-3"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-3"><center>{{$año}}</td>
                                <td class="py-2 px-3 rounded-tr-xl"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                            @endphp
                            @foreach($rentabilidad_año_anterior as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$rentabilidad_año_actual[$index]['rentabilidad'];
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($historico['rentabilidad'],0)}}%</td>
                                <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}%</td>
                                <td class="text-xs px-3 border"><center>{{($historico['rentabilidad']>0)?number_format(100*$dato_mes/($historico['rentabilidad'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Ticket Promedio</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Ticket Promedio</span></div>
                        <div class="w-full">
                        <canvas id="chartTicketPromedio" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td class="py-2 px-3"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-3"><center>{{$año}}</td>
                                <td class="py-2 px-3 rounded-tr-xl"><center>%var</td>
                            </tr>
                            @foreach($datos_año_anterior_array as $index=>$historico)
                            @php
                            try{
                                $dato_mes=($datos_año_array[$index]['monto_act']+$datos_año_array[$index]['monto_aep']+$datos_año_array[$index]['monto_ren']+$datos_año_array[$index]['monto_rep'])/($datos_año_array[$index]['act']+$datos_año_array[$index]['aep']+$datos_año_array[$index]['ren']+$datos_año_array[$index]['rep']);
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                            
                            }
                            try{
                                $dato_historico=($historico['monto_act']+$historico['monto_aep']+$historico['monto_ren']+$historico['monto_rep'])/($historico['act']+$historico['aep']+$historico['ren']+$historico['rep']);
                            }
                            catch(\Exception $e)
                            {
                                $dato_historico=0;
                            }
                            try{
                                $cambio_porc=($dato_mes/$dato_historico-1)*100;
                            }
                            catch(\Exception $e)
                            {
                                $cambio_porc=0;
                            }
                            
                            @endphp
                            <tr class="">
                                <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($dato_historico,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($cambio_porc,0)}}%</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Trafico</div>
            <div class="w-full flex flex-col md:flex-row">
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex flex-col">
                        <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Flujo en sucursales</span></div>
                        <div class="w-full">
                        <canvas id="chartFlujo" width="200" height="300"></canvas>
                        </div>
                    </div>
                </div>
                <div class="w-full md:w-1/2 p-4">
                    <div class="w-full flex justify-center">
                        <table>
                            <tr class="bg-blue-700 text-white rounder-t-xl">
                                <td class="rounded-tl-xl"></td>
                                <td class="py-2 px-3"><center>{{$año_anterior}}</td>
                                <td class="py-2 px-3"><center>{{$año}}</td>
                                <td class="py-2 px-3 rounded-tr-xl"><center>%var</td>
                            </tr>
                            @php
                                $ytd_ant=0;
                                $ytd_act=0;
                            @endphp
                            @foreach($flujo_año_anterior as $index=>$historico)
                            @php
                            try{
                                $dato_mes=$flujo_año_actual[$index]['flujo'];
                                $ytd_ant=$ytd_ant+$historico['flujo'];
                                $ytd_act=$ytd_act+$dato_mes;
                            }
                            catch(\Exception $e)
                            {
                                $dato_mes=0;
                            }
                            @endphp
                            <tr class="">
                                <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($historico['flujo'],0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{($historico['flujo']>0)?number_format(100*$dato_mes/($historico['flujo'])-100,0):0}}%</td>
                            </tr>
                            @endforeach
                            <tr class="font-bold bg-gray-200">
                                <td class="text-xs px-5 border">YTD</td>
                                <td class="text-xs px-3 border"><center>{{number_format($ytd_ant,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{number_format($ytd_act,0)}}</td>
                                <td class="text-xs px-3 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            @if($origen=="R" || $origen=="D")
            <div class="w-full flex flex-col pb-4">
                <div class="w-full p-4 bg-gray-200 text-gray-700 text-xl font-semibold border-b ">Detalles de : </div>
                @foreach($lista_dd as $detalle)
                <div class="w-full text-base font-semibold flex justify-center"><a href="/dashboard_comparativo/{{$periodo}}/{{($origen=="G"?'E':($origen=="R"?"G":"R"))}}/{{$detalle->llave}}/{{$detalle->value}}">{{$detalle->value}}</a></div>
                @endforeach
            </div>
            @endif
            </div>  
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.js"></script>   
<script>
var activaciones = document.getElementById("chartActivaciones").getContext('2d');
var activacionesCE = document.getElementById("chartActivacionesCE").getContext('2d');
var activacionesSE = document.getElementById("chartActivacionesSE").getContext('2d');
var renovaciones = document.getElementById("chartRenovaciones").getContext('2d');
var renovacionesCE = document.getElementById("chartRenovacionesCE").getContext('2d');
var renovacionesSE = document.getElementById("chartRenovacionesSE").getContext('2d');
var rentabilidad = document.getElementById("chartRentabilidad").getContext('2d');
var ticketPromedio = document.getElementById("chartTicketPromedio").getContext('2d');
var flujo = document.getElementById("chartFlujo").getContext('2d');

new Chart(activaciones, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->act+$actual->aep}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año_anterior as $anterior)
                {{$anterior->act+$anterior->aep}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ccf5ff', // Add custom color border (Line)
            backgroundColor: '#ccf5ff', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(activacionesCE, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->act}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año_anterior as $anterior)
                {{$anterior->act}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ccf5ff', // Add custom color border (Line)
            backgroundColor: '#ccf5ff', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(activacionesSE, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->aep}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año_anterior as $anterior)
                {{$anterior->aep}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ccf5ff', // Add custom color border (Line)
            backgroundColor: '#ccf5ff', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(renovaciones, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->ren+$actual->rep}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año_anterior as $anterior)
                {{$anterior->ren+$anterior->rep}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#b3ffe0', // Add custom color border (Line)
            backgroundColor: '#b3ffe0', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(renovacionesCE, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->ren}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año_anterior as $anterior)
                {{$anterior->ren}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#b3ffe0', // Add custom color border (Line)
            backgroundColor: '#b3ffe0', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(renovacionesSE, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->rep}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año_anterior as $anterior)
                {{$anterior->rep}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#b3ffe0', // Add custom color border (Line)
            backgroundColor: '#b3ffe0', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});

new Chart(rentabilidad, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($rentabilidad_año_actual as $actual)
                {{$actual['rentabilidad']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
            {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($rentabilidad_año_anterior as $anterior)
                {{$anterior['rentabilidad']}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ffff66', // Add custom color border (Line)
            backgroundColor: '#ffff66', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(ticketPromedio, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{($actual->monto_act+$actual->monto_aep+$actual->monto_ren+$actual->monto_rep)/($actual->act+$actual->aep+$actual->ren+$actual->rep)}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año_anterior as $anterior)
            {{($anterior->monto_act+$anterior->monto_aep+$anterior->monto_ren+$anterior->monto_rep)/($anterior->act+$anterior->aep+$anterior->ren+$anterior->rep)}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ffad99', // Add custom color border (Line)
            backgroundColor: '#ffad99', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
new Chart(flujo, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($flujo_año_actual as $actual)
                {{$actual['flujo']}},
            @endforeach
                  ],
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: {{$año_anterior}}, // Name the series
            data: [ // Specify the data values array
            @foreach($flujo_año_anterior as $anterior)
                {{$anterior['flujo']}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ccf5ff', // Add custom color border (Line)
            backgroundColor: '#ccf5ff', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension_:0.1
        }]
    },
    options: {
      responsive: true, // Instruct chart js to respond nicely.
      maintainAspectRatio: false, // Add to prevent default behaviour of full-width/height 
      scales: {
        y: {
            min: 0,
        }
        }
    }
});
</script>  
</x-app-layout>
