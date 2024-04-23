<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard') }}
    </x-slot>

    <div class="flex flex-col w-full text-gray-700 shadow-lg rounded-lg px-3 md:px-0">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Acceso a Indicadores</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>            
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full bg-white p-3 flex flex-col text-sm">
            Periodo&nbsp;&nbsp;
            <select class="w-1/2 md:w-1/6 rounded p-1 border border-gray-300" type="text" id="periodo">
                @foreach($periodos as $periodo)
                <option value="{{$periodo->periodo}}" {{session('periodo')==$periodo->periodo?'selected':''}}>{{$periodo->periodo}}</option>
                @endforeach
            </select>

        </div>
        @if(Auth::user()->puesto=='Director')
        <div class="py-3 w-full bg-gray-200 text-base px-3">
            Vista Direccion General
        </div>
        <div class="w-full bg-white pb-5">
            <div class="w-full flex flex-row">
                <div class="w-1/3 flex flex-col justify-center">
                    <div class="w-full pt-5 flex justify-center text-5xl font-semibold text-red-400">                        
                        <a href="javascript:general()"><i class="fas fa-building"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        General
                    </div>
                </div>
                <div class="w-1/3 flex flex-col justify-center">
                    <div class="w-full pt-5 flex justify-center text-5xl font-semibold text-blue-400">                        
                        <a href="javascript:comparativo()"><i class="fas fa-store"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Sucursales
                    </div>
                </div>
                <div class="w-1/3 flex flex-col">
                    <div class="w-full pt-5 flex justify-center text-5xl font-semibold text-yellow-500">
                        <a href="javascript:socios_comparativo()"><i class="far fa-handshake"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Socios
                    </div>
                </div>
            </div>
        </div>
        @endif
        <div class="py-3 w-full bg-gray-200 text-base px-3">
            Sucursales
        </div>
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row  justify-center text-center">
                <div class="w-1/3 flex flex-col justify-center text-center">
                    <div class="w-full pt-3 flex justify-center text-5xl font-semibold text-green-700">                        
                        <a href="javascript:efectividad()"><i class="fas fa-funnel-dollar"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dasboard Efectividad Flujo
                    </div>
                    <div class="w-full pt-12 flex justify-center text-5xl font-semibold text-blue-700">
                    <a href="javascript:interaccion()"><i class="fas fa-hands-helping"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dasboard Interaccion Tienda
                    </div>
                    <div class="w-full pt-12 flex justify-center text-5xl font-semibold text-yellow-700">
                        <a href="javascript:ordenes()"><i class="fas fa-file-signature"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dasboard Ordenes
                    </div>
                </div>
                <div class="w-1/3 flex flex-col">
                    <div class="w-full pt-3 flex justify-center text-5xl font-semibold text-green-700">
                        <a href="javascript:productividad()"><i class="far fa-check-circle"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dasboard Productividad
                    </div>
                    <div class="w-full pt-12 flex justify-center text-5xl font-semibold text-yellow-500">
                        <a href="javascript:resumen_periodo()"><i class="far fa-calendar-alt"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Resumen periodo
                    </div>
                    <div class="w-full pt-12 flex justify-center text-5xl font-semibold text-green-400">
                        <a href="javascript:resumen_efectividad()"><i class="fas fa-receipt"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Resumen efectividad
                    </div>
                </div>
                @if(Auth::user()->puesto!='Ejecutivo' && Auth::user()->puesto!='Otro')
                <div class="w-1/3 flex flex-col">
                    <div class="w-full pt-3 flex justify-center text-5xl font-semibold text-blue-500">
                        <a href="javascript:rentabilidad()"><i class="fas fa-balance-scale"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Rentabilidad
                    </div>
                    <div class="w-full pt-12 flex justify-center text-5xl font-semibold text-blue-700">
                        <a href="javascript:ejecutivo()"><i class="fas fa-tachometer-alt"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dashboard Ejecutivo
                    </div>
                    <div class="w-full pt-12 flex justify-center text-5xl font-semibold text-red-400">
                        <a href="javascript:comparativo()"><i class="fas fa-atlas"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dashboard Comparativos
                    </div>
                </div>
                @endif
            </div>
        </div>
        @if(Auth::user()->puesto=='Director')
        <div class="py-3 w-full bg-gray-200 text-base px-3">
            Socios Comerciales/Empresarial 
        </div>
        <div class="w-full bg-white pb-5 ">
            <div class="w-full flex flex-row  justify-center text-center">
                <div class="w-1/3 flex flex-col">
                    <div class="w-full pt-5 flex justify-center text-5xl font-semibold text-red-400">                        
                        <a href="javascript:socios_comparativo()"><i class="fas fa-atlas"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dasboard Comparativo
                    </div>
                </div>
                <div class="w-1/3 flex flex-col">
                    <div class="w-full pt-5 flex justify-center text-5xl font-semibold text-yellow-500">
                        <a href="javascript:socios_diario()"><i class="far fa-calendar-alt"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Avance Periodo
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    <script>
        function efectividad()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_efectividad/"+periodo;
        }
        function interaccion()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_interaccion/"+periodo;
        }
        function ordenes()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_orden/"+periodo;
        }
        function productividad()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_productividad/"+periodo;
        }
        function resumen_periodo()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_resumen_periodo/"+periodo;
        }
        function resumen_efectividad()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_resumen_efectividad/"+periodo;
        }
        function rentabilidad()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_rentabilidad/"+periodo;
        }
        function ejecutivo()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_ejecutivo/"+periodo;
        }
        function comparativo()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_comparativo/"+periodo;
        }
        function socios_comparativo()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/socios_comparativo/"+periodo;
        }
        function socios_diario()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/socios_diario/"+periodo;
        }
        function general()
        {
            periodo=document.getElementById("periodo").value;
            document.location.href="/dashboard_dg/"+periodo;
        }
    </script>
</x-app-layout>
