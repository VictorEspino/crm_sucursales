<x-app-layout>
    <x-slot name="header">
            {{ __('Plantilla Sucursal') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Plantilla Activa</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>                        
        </div> <!--FIN ENCABEZADO-->
        <div class="rounded-b-lg bg-white pt-8 pb-10 flex justify-center"> <!--CONTENIDO-->
        <table>
            <tr>
                <td class="bg-blue-600 font-sm text-white px-2"></td>
                <td class="bg-blue-600 font-sm text-white px-2">Puesto</td>
                <td class="bg-blue-600 font-sm text-white px-2">ATTUID</td>
                <td class="bg-blue-600 font-sm text-white px-2">VPN</td>
                <td class="bg-blue-600 font-sm text-white px-2">AVS</td>
                <td class="bg-blue-600 font-sm text-white px-2">PB</td>
                <td class="bg-blue-600 font-sm text-white px-2">NOE</td>
                <td class="bg-blue-600 font-sm text-white px-2">ASD</td>
            </tr>    

        <?php
        $plantilla=App\Models\User::where('udn',Auth::user()->udn)->where('estatus','Activo')->orderBy('puesto','asc')->get();
        $color=true;
        foreach($plantilla as $empleado)
        {
        ?>
            <tr>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2">({{$empleado->empleado}}) - {{$empleado->name}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2">{{$empleado->puesto}}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2"><center>{!!$empleado->attuid=='1'?'<i class="font-bold text-xl text-green-700 fas fa-check-circle"></i>':'<i class="font-bold text-xl text-red-700 fas fa-times"></i>'!!}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2"><center>{!!$empleado->vpn=='1'?'<i class="font-bold text-xl text-green-700 fas fa-check-circle"></i>':'<i class="font-bold text-xl text-red-700 fas fa-times"></i>'!!}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2"><center>{!!$empleado->avs=='1'?'<i class="font-bold text-xl text-green-700 fas fa-check-circle"></i>':'<i class="font-bold text-xl text-red-700 fas fa-times"></i>'!!}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2"><center>{!!$empleado->pb=='1'?'<i class="font-bold text-xl text-green-700 fas fa-check-circle"></i>':'<i class="font-bold text-xl text-red-700 fas fa-times"></i>'!!}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2"><center>{!!$empleado->noe=='1'?'<i class="font-bold text-xl text-green-700 fas fa-check-circle"></i>':'<i class="font-bold text-xl text-red-700 fas fa-times"></i>'!!}</td>
                <td class="{{$color?'bg-gray-200':'bg-white'}} font-xs text-gray-700 px-2"><center>{!!$empleado->asd=='1'?'<i class="font-bold text-xl text-green-700 fas fa-check-circle"></i>':'<i class="font-bold text-xl text-red-700 fas fa-times"></i>'!!}</td>
            </tr>   
        

        <?php
        $color=!$color;
        }
        ?>
        </table>
        
            
        </div> <!--FIN CONTENIDO-->
    </div>
</form>    

</x-app-layout>
