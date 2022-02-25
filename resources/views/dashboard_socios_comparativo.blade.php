<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard Comparativo') }}
    </x-slot>
    <div class="flex flex-col w-full  bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Socios comerciales - {{$periodo}}</div>
            <div class="w-full text-sm font-semibold text-red-400">Ultimo dia de información : {{$ultimo_dia}}</div>
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full  bg-white rounded-b-lg flex flex-wrap space-y-10">
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Contribucion<br />Socios Comerciales</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Contribucion</span></div>
                    <div class="w-full">
                    <canvas id="chartContribucion" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
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
                            $dato_mes=$datos_año_array[$index]['monto_act']+$datos_año_array[$index]['monto_ren'];
                            $ytd_ant=$ytd_ant+$historico['monto_act']+$historico['monto_ren'];
                            $ytd_act=$ytd_act+$dato_mes;
                          }
                          catch(\Exception $e)
                          {
                              $dato_mes=0;
                          }
                        @endphp
                        <tr class="">
                            <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                            <td class="text-xs px-3 border"><center>${{number_format($historico['monto_act']+$historico['monto_ren'],0)}}</td>
                            <td class="text-xs px-3 border"><center>${{number_format($dato_mes,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{($historico['monto_act']+$historico['monto_ren']>0)?number_format(100*$dato_mes/($historico['monto_act']+$historico['monto_ren'])-100,0):0}}%</td>
                        </tr>
                        @endforeach
                        <tr class="font-bold bg-gray-200">
                            <td class="text-xs px-5 border">YTD</td>
                            <td class="text-xs px-3 border"><center>${{number_format($ytd_ant,0)}}</td>
                            <td class="text-xs px-3 border"><center>${{number_format($ytd_act,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Operaciones<br />Socios Comerciales</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Operaciones</span></div>
                    <div class="w-full">
                    <canvas id="chartOperaciones" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
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
                            $dato_mes=$datos_año_array[$index]['act']+$datos_año_array[$index]['ren']+$datos_año_array[$index]['aep']+$datos_año_array[$index]['rep'];
                            $ytd_ant=$ytd_ant+$historico['act']+$historico['ren']+$historico['aep']+$historico['rep'];
                            $ytd_act=$ytd_act+$dato_mes;
                          }
                          catch(\Exception $e)
                          {
                              $dato_mes=0;
                          }
                        @endphp
                        <tr class="">
                            <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                            <td class="text-xs px-3 border"><center>{{number_format($historico['act']+$historico['ren']+$historico['aep']+$historico['rep'],0)}}</td>
                            <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{($historico['act']+$historico['ren']+$historico['aep']+$historico['rep'])?number_format(100*$dato_mes/($historico['act']+$historico['ren']+$historico['aep']+$historico['rep'])-100,0):0}}%</td>
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
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Activaciones Totales<br />Socios Comerciales</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones Totales</span></div>
                    <div class="w-full">
                    <canvas id="chartActivacionesTotales" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
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
                            <td class="text-xs px-3 border"><center>{{($historico['act']+$historico['aep'])?number_format(100*$dato_mes/($historico['act']+$historico['aep'])-100,0):0}}%</td>
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
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Lineas Renovadas<br />Socios Comerciales</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Lineas Renovadas</span></div>
                    <div class="w-full">
                    <canvas id="chartLineasRenovadas" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
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
                            $dato_mes=$datos_año_array[$index]['ren'];
                            $ytd_ant=$ytd_ant+$historico['ren'];
                            $ytd_act=$ytd_act+$dato_mes;
                          }
                          catch(\Exception $e)
                          {
                              $dato_mes=0;
                          }
                        @endphp
                        <tr class="">
                            <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                            <td class="text-xs px-3 border"><center>{{number_format($historico['ren'],0)}}</td>
                            <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{($historico['ren'])?number_format(100*$dato_mes/($historico['ren'])-100,0):0}}%</td>
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
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">ARPU<br />Socios Comerciales</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">ARPU</span></div>
                    <div class="w-full">
                    <canvas id="chartARPU" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
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
                            $dato_mes=($datos_año_array[$index]['act']+$datos_año_array[$index]['ren'])>0?($datos_año_array[$index]['monto_act']+$datos_año_array[$index]['monto_ren'])/($datos_año_array[$index]['act']+$datos_año_array[$index]['ren']):0;
                          }
                          catch(\Exception $e)
                          {
                              $dato_mes=0;
                          }
                        @endphp
                        <tr class="">
                            <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                            <td class="text-xs px-3 border"><center>${{($historico['act']+$historico['ren'])>0?number_format(($historico['monto_act']+$historico['monto_ren'])/($historico['act']+$historico['ren']),0):0}}</td>
                            <td class="text-xs px-3 border"><center>${{number_format($dato_mes,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{($historico['monto_act']+$historico['monto_ren']>0)?number_format(100*$dato_mes/(($historico['monto_act']+$historico['monto_ren'])/($historico['act']+$historico['ren']))-100,0):0}}%</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>            
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Activaciones AVS<br />Socios Comerciales</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">AVS</span></div>
                    <div class="w-full">
                    <canvas id="chartAVS" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center">
                    <table>
                        <tr class="bg-blue-700 text-white rounder-t-xl">
                            <td class="rounded-tl-xl"></td>
                            <td class="py-2 px-3"><center>{{$año_anterior}}</td>
                            <td class="py-2 px-3"><center>{{$año}}</td>
                            <td class="py-2 px-3 rounded-tr-xl"><center>%var</td>
                        </tr>
                        @foreach($avs_anterior_array as $index=>$historico)
                        @php
                          try{
                            $dato_mes=($avs_array[$index]['act']+$avs_array[$index]['aep']);
                          }
                          catch(\Exception $e)
                          {
                              $dato_mes=0;
                          }
                        @endphp
                        <tr class="">
                            <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                            <td class="text-xs px-3 border"><center>{{number_format(($historico['act']+$historico['aep']),0)}}</td>
                            <td class="text-xs px-3 border"><center>{{number_format($dato_mes,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{(($historico['act']+$historico['aep'])>0)?number_format(100*$dato_mes/($historico['act']+$historico['aep'])-100,0):0}}%</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>    
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Contribucion<br />Ambas áreas</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Contribucion</span></div>
                    <div class="w-full">
                    <canvas id="chartContribucionAmbas" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
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
                        @foreach($contribucion_ambas_anterior_array as $index=>$historico)
                        @php
                          try{
                            $dato_mes=$contribucion_ambas_array[$index]['contribucion'];
                            $ytd_ant=$ytd_ant+$historico['contribucion'];
                            $ytd_act=$ytd_act+$dato_mes;
                          }
                          catch(\Exception $e)
                          {
                              $dato_mes=0;
                          }
                        @endphp
                        <tr class="">
                            <td class="text-xs px-5 border">{{$meses[substr($historico['periodo'],5,2)]}}</td>
                            <td class="text-xs px-3 border"><center>${{number_format($historico['contribucion'],0)}}</td>
                            <td class="text-xs px-3 border"><center>${{number_format($dato_mes,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{($historico['contribucion']>0)?number_format(100*$dato_mes/($historico['contribucion'])-100,0):0}}%</td>
                        </tr>
                        @endforeach
                        <tr class="font-bold bg-gray-200">
                            <td class="text-xs px-5 border">YTD</td>
                            <td class="text-xs px-3 border"><center>${{number_format($ytd_ant,0)}}</td>
                            <td class="text-xs px-3 border"><center>${{number_format($ytd_act,0)}}</td>
                            <td class="text-xs px-3 border"><center>{{($ytd_ant>0)?number_format(100*$ytd_act/($ytd_ant)-100,0):0}}%</td>
                        </tr>
                    </table>
                </div>
            </div>        
        </div>  
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.js"></script>   
<script>
var contribucion = document.getElementById("chartContribucion").getContext('2d');
var operaciones = document.getElementById("chartOperaciones").getContext('2d');
var activacionesTotales = document.getElementById("chartActivacionesTotales").getContext('2d');
var lineasRenovadas = document.getElementById("chartLineasRenovadas").getContext('2d');
var arpu = document.getElementById("chartARPU").getContext('2d');
var avs = document.getElementById("chartAVS").getContext('2d');
var contribucionAmbas = document.getElementById("chartContribucionAmbas").getContext('2d');

new Chart(contribucion, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->monto_act+$actual->monto_ren}},
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
                {{$anterior->monto_act+$anterior->monto_ren}},
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
new Chart(operaciones, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series            
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{$actual->act+$actual->ren+$actual->aep+$actual->rep}},
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
                {{$anterior->act+$anterior->ren+$anterior->aep+$anterior->rep}},
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
new Chart(activacionesTotales, {
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
            borderColor: '#ebc660', // Add custom color border (Line)
            backgroundColor: '#ebc660', // Add custom color background (Points and Fill)
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
new Chart(lineasRenovadas, {
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
            borderColor: '#dcc6f7', // Add custom color border (Line)
            backgroundColor: '#dcc6f7', // Add custom color background (Points and Fill)
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
new Chart(arpu, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series
            data: [ // Specify the data values array
            @foreach($datos_año as $actual)
                {{($actual->act+$actual->ren)>0?($actual->monto_act+$actual->monto_ren)/($actual->act+$actual->ren):0}},
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
                {{($anterior->act+$anterior->ren)>0?($anterior->monto_act+$anterior->monto_ren)/($anterior->act+$anterior->ren):0}},
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
new Chart(avs, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series            
            data: [ // Specify the data values array
            @foreach($datos_avs as $actual)
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
            @foreach($datos_avs_anterior as $anterior)
                {{$anterior->act+$anterior->aep}},
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
new Chart(contribucionAmbas, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: {{$año}}, // Name the series            
            data: [ // Specify the data values array
            @foreach($contribucion_ambas as $actual)
                {{$actual->contribucion}},
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
            @foreach($contribucion_ambas_anterior as $anterior)
                {{$anterior->contribucion}},
            @endforeach
                  ],
            fill: true,
            borderColor: '#ebc660', // Add custom color border (Line)
            backgroundColor: '#ebc660', // Add custom color background (Points and Fill)
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
