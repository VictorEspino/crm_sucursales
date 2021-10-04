<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Acceso a Indicadores</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>            
        </div> <!--FIN ENCABEZADO-->
        <div class="w-full bg-white p-3 flex flex-col text-sm">
            Periodo&nbsp;&nbsp;
            <select class="w-1/6 rounded p-1 border border-gray-300" type="text" id="periodo">
                <option value="2021-09" {{session('periodo')=='2021-09'?'selected':''}}>Septiembre 2021</option>
                <option value="2021-10" {{session('periodo')=='2021-10'?'selected':''}}>Octubre 2021</option>
            </select>

        </div>
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row">
                <div class="w-1/2 flex flex-col">
                    <div class="w-full pt-3 flex justify-center text-5xl font-semibold text-green-700">                        
                        <a href="javascript:efectividad()"><i class="fas fa-funnel-dollar"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dasboard Efectividad
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
                <div class="w-1/2 flex flex-col">
                    <div class="w-full pt-3 flex justify-center text-5xl font-semibold text-green-700">
                    <a href="javascript:productividad()"><i class="far fa-check-circle"></i></a>
                    </div>
                    <div class="w-full flex justify-center">
                        Dasboard Productividad
                    </div>
                </div>
            </div>
        </div>
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
    </script>
</x-app-layout>
