<x-app-layout>
    <x-slot name="header">
            {{ __('Objetivos cargados') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Objetivos Cargados (Vista Mensual)</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>                        
        </div> <!--FIN ENCABEZADO-->
        <div class="rounded-b-lg bg-white pt-8 pb-10 px-4 flex justify-center"> <!--CONTENIDO-->
        <table>
            <tr>
                <td class="bg-blue-600 text-sm text-white px-2"><center>Objetivos Cargados</td>
                <td class="bg-blue-600 text-sm text-white px-2"><center>UDN</td>
                <td class="bg-blue-600 text-sm text-white px-2">Sucursal</td>
                <td class="bg-blue-600 text-sm text-white px-2"><center>Activaciones CON Equipo</td>
                <td class="bg-blue-600 text-sm text-white px-2"><center>Activaciones SIN Equipo</td>
                <td class="bg-blue-600 text-sm text-white px-2"><center>Activaciones CON Equipo</td>
                <td class="bg-blue-600 text-sm text-white px-2"><center>Activaciones SIN Equipo</td>
                <td class="bg-blue-600 text-sm text-white px-2"><center>Ejecutivos</td>
                <td class="bg-blue-600 text-sm text-white px-2"><center>Min Diarios por Ejecutivo</td>
            </tr>    

        <?php
        $color=true;
        $region="";
        foreach($registros as $registro)
        {
            if($registro->region!=$region)
            {
        ?>
            <tr>
                <td class="font-bold text-sm bg-gray-500 text-gray-100" colspan=9><center>{{$registro->region}}</td>
            </tr>    
        <?php
            }
        ?>
            <tr>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{!!$registro->min_diario!="0"?'<i class="font-bold text-xl text-green-700 fas fa-check-circle"></i>':'<i class="font-bold text-xl text-red-700 fas fa-times"></i>'!!}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{{$registro->udn}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2">{{$registro->pdv}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{{$registro->ac}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{{$registro->asi}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{{$registro->rc}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{{$registro->rs}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{{$registro->ejecutivos}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} text-xs text-gray-700 px-2"><center>{{$registro->min_diario}}</td>
                
            </tr>   
        

        <?php
        $region=$registro->region;
        $color=!$color;
        }
        ?>
        </table>
        
            
        </div> <!--FIN CONTENIDO-->
    </div>
</form>    

</x-app-layout>
