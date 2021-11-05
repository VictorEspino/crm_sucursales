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
        <div class="w-full  bg-white rounded-b-lg flex flex-wrap space-y-10">
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Activaciones</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones</span></div>
                    <div class="w-full">
                    <canvas id="myChart" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center">
                    <table>
                        <tr class="bg-blue-700 text-white rounder-t-xl">
                            <td class="rounded-tl-xl"></td>
                            <td class="py-2 rounded-tr-xl" colspan=3><center>YTD</td>                            
                        </tr>
                        <tr class="bg-blue-700 text-white">
                            <td class=""></td>
                            <td class="py-2 px-3"><center>2020</td>
                            <td class="py-2 px-3"><center>2021</td>
                            <td class="py-2 px-3"><center>%var</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">Activaciones</td>
                            <td class="py-2 px-3 border"><center>33,256</td>
                            <td class="py-2 px-3 border"><center>35,825</td>
                            <td class="py-2 px-3 border"><center>+7%</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">CON Equipo</td>
                            <td class="py-2 px-3 border"><center>15,284</td>
                            <td class="py-2 px-3 border"><center>12,655</td>
                            <td class="py-2 px-3 border"><center>-18%</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">SIN Equipo</td>
                            <td class="py-2 px-3 border"><center>19,172</td>
                            <td class="py-2 px-3 border"><center>19,170</td>
                            <td class="py-2 px-3 border"><center>0%</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="w-1/2 p-4 flex flex-col">
                <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones CON equipo</span></div>
                    <div class="w-full">
                    <canvas id="myChart2" width="200" height="300"></canvas>
                    </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Activaciones SIN equipo</span></div>
                    <div class="w-full">
                    <canvas id="myChart3" width="200" height="300"></canvas>
                    </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Renovaciones</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones</span></div>
                    <div class="w-full">
                    <canvas id="myChart4" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center">
                    <table>
                        <tr class="bg-blue-700 text-white rounder-t-xl">
                            <td class="rounded-tl-xl"></td>
                            <td class="py-2 rounded-tr-xl" colspan=3><center>YTD</td>                            
                        </tr>
                        <tr class="bg-blue-700 text-white">
                            <td class=""></td>
                            <td class="py-2 px-3"><center>2020</td>
                            <td class="py-2 px-3"><center>2021</td>
                            <td class="py-2 px-3"><center>%var</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">Renovaciones</td>
                            <td class="py-2 px-3 border"><center>28,561</td>
                            <td class="py-2 px-3 border"><center>24,528</td>
                            <td class="py-2 px-3 border"><center>-15%</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">CON Equipo</td>
                            <td class="py-2 px-3 border"><center>17,881</td>
                            <td class="py-2 px-3 border"><center>12,655</td>
                            <td class="py-2 px-3 border"><center>-30%</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">SIN Equipo</td>
                            <td class="py-2 px-3 border"><center>10,680</td>
                            <td class="py-2 px-3 border"><center>11,863</td>
                            <td class="py-2 px-3 border"><center>+11%</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="w-1/2 p-4 flex flex-col">
                <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones CON equipo</span></div>
                    <div class="w-full">
                    <canvas id="myChart5" width="200" height="300"></canvas>
                    </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Renovaciones SIN equipo</span></div>
                    <div class="w-full">
                    <canvas id="myChart6" width="200" height="300"></canvas>
                    </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Rentabilidad</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Rentabilidad</span></div>
                    <div class="w-full">
                    <canvas id="myChart7" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center">
                    <table>
                        <tr class="bg-blue-700 text-white rounder-t-xl">
                            <td class="rounded-tl-xl"></td>
                            <td class="py-2 rounded-tr-xl" colspan=3><center>YTD</td>                            
                        </tr>
                        <tr class="bg-blue-700 text-white">
                            <td class=""></td>
                            <td class="py-2 px-3"><center>2020</td>
                            <td class="py-2 px-3"><center>2021</td>
                            <td class="py-2 px-3"><center>%var</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">Rentabilidad</td>
                            <td class="py-2 px-3 border"><center>85%</td>
                            <td class="py-2 px-3 border"><center>98%</td>
                            <td class="py-2 px-3 border"><center>+15%</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Ticket Promedio</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Ticket Promedio</span></div>
                    <div class="w-full">
                    <canvas id="myChart8" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center">
                    <table>
                        <tr class="bg-blue-700 text-white rounder-t-xl">
                            <td class="rounded-tl-xl"></td>
                            <td class="py-2 rounded-tr-xl" colspan=3><center>YTD</td>                            
                        </tr>
                        <tr class="bg-blue-700 text-white">
                            <td class=""></td>
                            <td class="py-2 px-3"><center>2020</td>
                            <td class="py-2 px-3"><center>2021</td>
                            <td class="py-2 px-3"><center>%var</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">Ticket Promedio</td>
                            <td class="py-2 px-3 border"><center>$344</td>
                            <td class="py-2 px-3 border"><center>$345</td>
                            <td class="py-2 px-3 border"><center>0%</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="w-full p-3 text-base font-semibold bg-gray-200 text-gray-700">Trafico</div>
            <div class="w-1/2 p-4">
                <div class="w-full flex flex-col">
                    <div class="w-full flex justify-center"><span class="text-base font-semibold text-gray-700">Flujo en sucursales</span></div>
                    <div class="w-full">
                    <canvas id="myChart9" width="200" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="w-1/2 p-4">
                <div class="w-full flex justify-center">
                    <table>
                        <tr class="bg-blue-700 text-white rounder-t-xl">
                            <td class="rounded-tl-xl"></td>
                            <td class="py-2 rounded-tr-xl" colspan=3><center>YTD</td>                            
                        </tr>
                        <tr class="bg-blue-700 text-white">
                            <td class=""></td>
                            <td class="py-2 px-3"><center>2020</td>
                            <td class="py-2 px-3"><center>2021</td>
                            <td class="py-2 px-3"><center>%var</td>
                        </tr>
                        <tr class="">
                            <td class="py-2 px-5 border">Trafico</td>
                            <td class="py-2 px-3 border"><center>565,212</td>
                            <td class="py-2 px-3 border"><center>558,654</td>
                            <td class="py-2 px-3 border"><center>-2%</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>  
    </div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.js"></script>   
<script>
var ctx = document.getElementById("myChart").getContext('2d');
var ctx2 = document.getElementById("myChart2").getContext('2d');
var ctx3 = document.getElementById("myChart3").getContext('2d');
var ctx4 = document.getElementById("myChart4").getContext('2d');
var ctx5 = document.getElementById("myChart5").getContext('2d');
var ctx6 = document.getElementById("myChart6").getContext('2d');
var ctx7 = document.getElementById("myChart7").getContext('2d');
var ctx8 = document.getElementById("myChart8").getContext('2d');
var ctx9 = document.getElementById("myChart9").getContext('2d');

var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [3600,3800,3300,2400,2900,3400,3900,4000,3900,3800], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [3400,3200,3100,2800,3000,3900,3900,3500,3200,3300,3950,4000], // Specify the data values array
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
var myChart2 = new Chart(ctx2, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [1620,1710,1485,1080,1305,1530,1755,1800,1755,1710], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [1530,1440,1395,1260,1350,1755,1755,1575,1440,1485,1778,1800], // Specify the data values array
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
var myChart3 = new Chart(ctx3, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [1870,1760,1705,1540,1650,2145,2145,1925,1760,1815], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [1870,1760,1705,1540,1650,2145,2145,1925,1760,1815,2174,2200], // Specify the data values array
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
var myChart4 = new Chart(ctx4, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [2520,2660,2310,1680,2030,2380,2730,2800,2730,2660], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [2380,2240,2170,1960,2100,2730,2730,2450,2240,2310,2765,2800], // Specify the data values array
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
var myChart5 = new Chart(ctx5, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [1134,1197,1039,756,913,1071,1228,1260,1228,1197], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [1071,1008,976,882,945,1228,1228,1102,1008,1039,1244,1260], // Specify the data values array
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
var myChart6 = new Chart(ctx6, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [1309,1232,1193,1078,1155,1501,1501,1347,1232,1270], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [1309,1232,1193,1078,1155,1501,1501,1347,1232,1270,1520,1540], // Specify the data values array
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
var myChart7 = new Chart(ctx7, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [0.92,0.93,0.92,0.95,0.96,0.94,0.96,0.97,1.01,1.04], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [0.8,0.81,0.8,0.82,0.82,0.83,0.82,0.85,0.86,0.87,0.92,0.98], // Specify the data values array
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
var myChart8 = new Chart(ctx8, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [350,351,342,343,344,344,342,343,342,344], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [344,342,345,360,322,344,346,324,351,340,320,340], // Specify the data values array
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
var myChart9 = new Chart(ctx9, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [54000,54000,51000,53000,54000,56000,58000,59000,58000,59000], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [52000,53000,54000,55000,56000,57000,58000,59000,60000,61000,62000,63000], // Specify the data values array
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
var myChart8 = new Chart(ctx8, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [500,	50,	2424,	14040,	14141,	4111,	4544,	47,	5555, 6811], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [1288,	88942,	44545,	7588,	99,	242,	1417,	5504,	75, 457], // Specify the data values array
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
var myChart9 = new Chart(ctx9, {
    type: 'line',
    data: {
        labels: ["Ene",	"Feb","Mar","Abr","May","Jun","Jul","Ago","Sep","Oct","Nov","Dic"],
        datasets: [{
            label: '2021', // Name the series
            data: [500,	50,	2424,	14040,	14141,	4111,	4544,	47,	5555, 6811], // Specify the data values array
            fill: false,
            borderColor: '#FF0000', // Add custom color border (Line)
            backgroundColor: '#FF0000', // Add custom color background (Points and Fill)
            borderWidth: 1, // Specify bar border width
            tension:0.1
        },
                  {
            label: '2020', // Name the series
            data: [1288,	88942,	44545,	7588,	99,	242,	1417,	5504,	75, 457], // Specify the data values array
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
