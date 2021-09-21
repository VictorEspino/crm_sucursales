<?php
$efectividad_visitas=App\Http\Controllers\DashboardsController::gauge("1");
$efectividad_ordenes=App\Http\Controllers\DashboardsController::gauge("2");
$efectividad_intencion=App\Http\Controllers\DashboardsController::gauge("3");
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.6.0/dist/alpine.js" defer></script>

<!-- PARA EL DASHBOARD -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss/dist/tailwind.min.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <style>
        .bg-side-nav {
             background-color: #ECF0F1;
            }

    </style>

<script type="text/javascript">
      google.charts.load('current', {'packages':['gauge']});
      google.charts.setOnLoadCallback(drawChart1);
      google.charts.setOnLoadCallback(drawChart2);
      google.charts.setOnLoadCallback(drawChart3);

      function drawChart1() {

        var data = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['', {{$efectividad_visitas}}],
        ]);

        var options = {
          width: 300, height: 110,
          yellowFrom:0, yellowTo: 10,
          greenFrom:10, greenTo: 100,
          minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
      function drawChart2() {

        var data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['', {{$efectividad_ordenes}}],
        ]);

        var options = {
        width: 300, height: 110,
        redFrom: 0, redTo: 60,
        yellowFrom:60, yellowTo: 90,
        greenFrom:90, greenTo: 100,
        minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div_2'));

        chart.draw(data, options);
        }
        function drawChart3() {

        var data = google.visualization.arrayToDataTable([
        ['Label', 'Value'],
        ['', {{$efectividad_intencion}}],
        ]);

        var options = {
        width: 300, height: 110,
        redFrom: 0, redTo: 60,
        yellowFrom:60, yellowTo: 90,
        greenFrom:90, greenTo: 100,
        minorTicks: 5
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div_1'));

        chart.draw(data, options);
        }
    </script>
    
    </head>
    
    <body class="font-sans antialiased font-light" >
        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            <header class="bg-blue-900">
                <div class="max-w-7xl mx-auto py-6 px-2 sm:px-2 lg:px-4">
                    <h2 class="font-semibold text-l text-gray-100 leading-tight bg-blue-900">

                            <i class="fas fa-bars pr-2 text-white" onclick="sidebarToggle()"></i>

                            {{ $header }}
                    </h2>
                </div>
            </header>

            <!-- Page Content -->
            <main>
            <div class="flex -mb-4">
                <!--

                bg-side-nav w-1/2 md:w-1/6 lg:w-1/6 border-r border-side-nav hidden md:block lg:block
                -->
                <div id="sidebar" class="bg-gray-300 text-gray-700 h-screen flex w-52 flex-shrink-0 border-r border-side-nav md:block lg:block">
                    
                    <div>
                        <ul class="list-reset flex flex-col">
                            <li class=" w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('dashboard')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('dashboard') }}" 
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-tachometer-alt float-left mx-2"></i>
                                    Dashboard
                                    <span><i class="fas fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            @if(Auth::user()->puesto=='Regional' || Auth::user()->puesto=='Gerente')
                            <li class=" w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('objetivo_update')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('objetivo_update') }}" 
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-tachometer-alt float-left mx-2"></i>
                                    Objetivos
                                    <span><i class="fas fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border bg-blue-200">
                                Plantilla
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('plantilla_nuevo')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('plantilla_nuevo') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-user float-left mx-2"></i>
                                      Registrar Nuevo
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('plantilla_update')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('plantilla_update') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-users float-left mx-2"></i>
                                      Actualizacion
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            @if(Auth::user()->puesto=='Gerente')
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('plantilla_sucursal')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('plantilla_sucursal') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-users float-left mx-2"></i>
                                      Sucursal
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            @endif
                            @endif
                            <li class="w-full h-full py-3 px-2 border-b border-light-border bg-blue-200">
                                Actividad
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('interaccion_nuevo')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('interaccion_nuevo') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-handshake float-left mx-2"></i>
                                      Registro Interaccion
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('funnel_nuevo')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('funnel_nuevo') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-funnel-dollar float-left mx-2"></i>
                                      Registro Funnel
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('seguimiento_funnel')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('seguimiento_funnel') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-table float-left mx-2"></i>
                                      Seguimiento Funnel
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('orden_nuevo')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('orden_nuevo') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-file-contract float-left mx-2"></i>
                                      Registro Orden
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('seguimiento_orden')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('seguimiento_orden') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-table float-left mx-2"></i>
                                      Seguimiento Orden
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('demanda_nuevo')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('demanda_nuevo') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-search-dollar float-left mx-2"></i>
                                      Generacion Demanda
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                            <li class="w-full h-full py-3 px-2 border-b border-light-border {{request()->routeIs('incidencia_nuevo')?'bg-gray-100':'br-gray-800'}}">
                                <a href="{{ route('incidencia_nuevo') }}"
                                    class="font-sans font-hairline hover:font-normal text-sm text-nav-item no-underline">
                                    <i class="fas fa-indent float-left mx-2"></i>
                                      Registro de Incidencia
                                    <span><i class="fa fa-angle-right float-right"></i></span>
                                </a>
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="w-full py-5 sm:px-6 lg:px-8">
                   <!-- <div class="overflow-hidden shadow-xl sm:rounded-lg"> -->
                    {{ $slot }}
                   <!-- </div> -->
                
                </div>
            </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts
        <script>
            var sidebar = document.getElementById('sidebar');

            function sidebarToggle() {
                if(sidebar.style.display!="none") {
                    sidebar.style.display="none";
                }
                else{
                    sidebar.style.display="block";
                }
            }    
            function estado_cuenta_interno()
            {
                empleado=document.getElementById("numero_empleado_menu").value;
                if(empleado!="" && empleado!="0")
                {
                 window.open('/estado_cuenta_interno/0/'+empleado+'/0','popup','width=1300,height=900, location=no, addressbar=no');
                }
                if(empleado=="" || empleado=="0")
                {
                    alert("Por favor indique un numero de empleado para consultar");
                } 
            }    
            
        </script>

    </body>
    
</html>
