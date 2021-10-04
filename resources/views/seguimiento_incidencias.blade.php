<x-app-layout>
    <x-slot name="header">
            {{ __('Incidencias - Seguimiento') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Seguimiento Incidencias</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>            
        </div> <!--FIN ENCABEZADO-->
        
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row">
                <div class="w-1/3">
                    <form action="{{route('seguimiento_incidencias')}}" class="">
                        <input class="w-8/12 rounded p-1 border border-gray-300" type="date" name="query" value="{{$query}}"> 
                        <button class="rounded p-1 border bg-green-500 hover:bg-green-700 text-gray-100 font-semibold">Buscar</button>
                    </form>
                </div>
                <div>
                    <form action="{{route('seguimiento_incidencias')}}" class="">
                        <button class="rounded p-1 border bg-blue-500 hover:bg-blue-700 text-gray-100 font-semibold">Todos</button>
                    </form>
                </div>
            </div>

            <div class="flex justify-end text-xs">
                {{$registros->links()}}
            </div>
            <div class="w-full flex justify-center pt-5 flex-col"> <!--TABLA DE CONTENIDO-->
                <div class="w-full flex justify-center pb-3"><span class="font-semibold text-sm text-gray-700">Registros Incidencias</span></div>
                <div class="w-full flex justify-center">
                <table>
                    <tr class="">
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"></td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Ejecutivo</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Dia Incidencia</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Dia Incidencia</td>
                    </tr>
                <?php
                    $color=false;
                    foreach($registros as $registro)
                    {
                ?>
                    <tr class="">
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-red-700 p-1 text-xl"><center><a href="javascript:borrar_incidencia({{$registro->id}},'{{$registro->dia_incidencia}}','{{$registro->tipo}}')"><i class="far fa-trash-alt"></i></a></td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$registro->nombre}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$registro->dia_incidencia}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$registro->tipo}}</td>
                    </tr>
                <?php
                    $color=!$color;
                    }
                ?>
                </table>
                </div>
            </div><!--FIN DE TABLA -->

        </div> <!-- FIN DEL CONTENIDO -->
    </div> <!--DIV PRINCIPAL-->

    
        <script>
            
            function borrar_incidencia(id,dia,tipo)
            {
                if(confirm("Ha indicado borrar la incidencia de "+tipo+" del dia "+dia+"\n\n¿Desea continuar?"))
                {
                    var xmlhttp = new XMLHttpRequest();
                    xmlhttp.open("POST", "/incidencia_borrar", true);
                    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xmlhttp.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            document.location.reload();
                            }
                    }; 
                    xmlhttp.onerror = function () {
                    alert("** Un error ha ocurrido en la actualizacion");
                    };
                    parametros="_token="+"{{csrf_token()}}"
                    parametros+="&id="+id;
                    xmlhttp.send(parametros);
                }
            }
        </script>
</x-app-layout>
