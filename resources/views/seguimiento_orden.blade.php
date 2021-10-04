<x-app-layout>
    <x-slot name="header">
            {{ __('Orden - Seguimiento') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Seguimiento Orden</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>            
        </div> <!--FIN ENCABEZADO-->
        
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full">
                <form action="{{route('seguimiento_orden')}}" class="">
                    <input class="w-1/3 rounded p-1 border border-gray-300" type="text" name="query" value="{{$query}}" placeholder="Buscar cliente/orden"> 
                    <button class="rounded p-1 border bg-green-500 hover:bg-green-700 text-gray-100 font-semibold">Buscar</button>
                </form>
            </div>
            <div class="flex justify-end text-xs">
                {{$registros->links()}}
            </div>
            <div class="w-full flex justify-center pt-5 flex-col"> <!--TABLA DE CONTENIDO-->
                <div class="w-full flex justify-center pb-3"><span class="font-semibold text-sm text-gray-700">Registros Ordenes</span></div>
                <div class="w-full flex justify-center">
                <table>
                    <tr class="">
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"></td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Ejecutivo</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Origen</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Cliente</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Orden</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Producto</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Plan</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Estatus</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Riesgo</td>
                        <td class="border border-gray-300 font-semibold bg-blue-500 text-gray-200 p-1 text-sm"><center>Creacion</td>
                    </tr>
                <?php
                    $color=false;
                    foreach($registros as $orden)
                    {
                ?>
                    <tr class="">
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs"><center><a href="javascript:detalles({{$orden->id}})"><i class="far fa-edit"></i></td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->nombre}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->origen}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->cliente}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->numero_orden}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->producto}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->plan}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->estatus_final}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->riesgo}}</td>
                        <td class="border border-gray-300 font-light {{$color?'bg-gray-100':''}} text-gray-700 p-1 text-xs">{{$orden->created_at}}</td>
                    </tr>
                <?php
                    $color=!$color;
                    }
                ?>
                </table>
                </div>
            </div><!--FIN DE TABLA -->

        </div> <!-- FIN DEL CONTENIDO -->
    </div> <!--DIV PRINCIPAL -->

<!--MODAL DE DETALLES-->
<div id="detalles" class="hidden overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none justify-center items-center flex">
            <div class="relative w-3/5 my-6 mx-auto max-w-3xl">
                <!--content-->
                <div class="p-3 border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none">
                    <!--header-->
                    <div class="flex items-start justify-between p-3 border-b border-solid border-grey-300 rounded-t">
                        <h3 class="text-xl font-semibold">
                            Detalles Orden
                        </h3>
                        <button class="p-1 ml-auto bg-transparent border-0 text-black opacity-5 float-right text-3xl leading-none font-semibold outline-none focus:outline-none" onClick="close_detalles()">
                            <span class="bg-transparent text-black opacity-5 h-6 w-6 text-2xl block outline-none focus:outline-none">
                                ×
                            </span>
                        </button>
                    </div>
                    <!--body-->
                    <div class="flex flex-col p-3">
                        <div class="py-2 w-full flex flex-row text-xs text-gray-700 space-x-2">
                            <div class="w-full">
                                Cliente <input class="w-full rounded p-1 border border-white" type="text" id="cliente">
                                <input class="hidden" type="text" id="id">
                            </div>
                        </div>
                        <div class="py-2 w-full flex flex-row text-xs text-gray-700 space-x-2">
                            <div class="w-1/3">
                                Origen <input class="w-full rounded p-1 border border-white" type="text" id="origen" readonly>
                            </div>
                            <div class="w-1/3">
                                Telefono <input class="w-full rounded p-1 border border-white" type="text" id="telefono">
                            </div>
                            <div class="w-1/3">
                                Correo <input class="w-full rounded p-1 border border-white" type="text" id="correo">
                            </div>
                        </div>
                        <div class="py-2 w-full flex flex-row text-xs text-gray-700 space-x-2">
                            <div class="w-1/4">
                                Producto <input class="w-full rounded p-1 border border-white" type="text" id="producto" readonly>
                            </div>
                            <div class="w-1/4">
                                Plan <input class="w-full rounded p-1 border border-white" type="text" id="plan">
                            </div>
                            <div class="w-1/4">
                                Equipo <input class="w-full rounded p-1 border border-white" type="text" id="equipo">
                            </div>
                            <div class="w-1/4">
                                Renta <input class="w-full rounded p-1 border border-white" type="text" id="renta">
                            </div>
                        </div>
                        <div class="py-2 w-full flex flex-row text-xs text-gray-700 space-x-2">
                        <div class="w-1/4">
                                Orden <input class="w-full rounded p-1 border border-white" type="text" id="orden" readonly>
                            </div>
                            <div class="w-1/4">
                                Porcentaje Requerido <input class="w-full rounded p-1 border border-white" type="text" id="porcentaje_requerido" readonly>
                            </div>
                            <div class="w-1/4">
                                Monto Total <input class="w-full rounded p-1 border border-white" type="text" id="monto_total" readonly>
                            </div>
                            <div class="w-1/4">
                                Riesgo <input class="w-full rounded p-1 border border-white" type="text" id="riesgo" readonly>
                            </div>
                        </div>
                        <div class="py-2 w-full flex flex-row text-xs text-gray-700 space-x-2">
                            <div class="w-full">
                                Estatus Final
                                <select class="w-full rounded p-1 border border-gray-300" type="text" id="estatus_final">
                                    <option value=""></option>
                                <?php  
                                        $estatuss=App\Models\CatalogoEstatus::all();
                                        foreach ($estatuss as $estatus) {
                                ?>
                                    <option value="{{$estatus->estatus}}" class="">{{$estatus->estatus}}</option>
                                <?php
                                    }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="py-2 w-full flex flex-row text-xs text-gray-700 space-x-2">
                            <div class="w-full">
                                Observaciones
                                <textarea class="w-full rounded p-1 border border-gray-300" id="observaciones"></textarea>
                            </div>
                        </div>
                    </div>
                        <!--footer-->
                    <div class="flex items-center justify-end p-2 border-t border-solid border-gray-300 rounded-b">
                        <button class="rounded p-1 border bg-green-500 hover:bg-green-700 text-gray-100 font-semibold" onClick="guardar_detalles()">Guardar</button>&nbsp;&nbsp;
                        <button class="rounded p-1 border bg-red-500 hover:bg-red-700 text-gray-100 font-semibold" onClick="close_detalles()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
        <div id="detalles2" class="hidden opacity-25 fixed inset-0 z-40 bg-black"></div>
        <script>
            function detalles(id)
            {
                document.getElementById("detalles").style.display="block";
                document.getElementById("detalles2").style.display="block";             
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        //document.getElementById("txtHint").innerHTML = this.responseText;
                        if(this.responseText!='')
                        {
                            respuesta=JSON.parse(this.response);
                            //console.log(this.responseText);
                            document.getElementById("id").value=respuesta.id;
                            document.getElementById("origen").value=respuesta.origen;
                            document.getElementById("cliente").value=respuesta.cliente;
                            document.getElementById("telefono").value=respuesta.telefono;
                            document.getElementById("correo").value=respuesta.correo;
                            document.getElementById("producto").value=respuesta.producto;
                            document.getElementById("plan").value=respuesta.plan;
                            document.getElementById("equipo").value=respuesta.equipo;
                            document.getElementById("renta").value=respuesta.renta;
                            document.getElementById("orden").value=respuesta.numero_orden;
                            document.getElementById("estatus_final").value=respuesta.estatus_final;
                            document.getElementById("porcentaje_requerido").value=respuesta.porcentaje_requerido;
                            document.getElementById("monto_total").value=respuesta.monto_total;
                            document.getElementById("riesgo").value=respuesta.riesgo;
                            document.getElementById("observaciones").value=respuesta.observaciones;

                        }
                        else
                        {
                            document.getElementById("id").value='';
                            document.getElementById("origen").value='';
                            document.getElementById("cliente").value='';
                            document.getElementById("telefono").value='';
                            document.getElementById("correo").value='';
                            document.getElementById("producto").value='';
                            document.getElementById("plan").value='';
                            document.getElementById("equipo").value='';
                            document.getElementById("renta").value='';
                            document.getElementById("orden").value='';
                            document.getElementById("estatus_final").value='';
                            document.getElementById("porcentaje_requerido").value='';
                            document.getElementById("monto_total").value='';
                            document.getElementById("riesgo").value='';
                            document.getElementById("observaciones").value='';
                            alert("Error al consultar la base de datos, intente nuevamente!");
                        }
        
                    }
                };  
                xmlhttp.open("GET", "/orden_detalles/" + id, true);
                xmlhttp.send();
                
            }
            function close_detalles()
            {
                document.getElementById("detalles").style.display="none";
                document.getElementById("detalles2").style.display="none";
            }
            function guardar_detalles()
            {
                document.getElementById("detalles").style.display="none";
                document.getElementById("detalles2").style.display="none";
                id=document.getElementById("id").value;
                estatus_final=document.getElementById("estatus_final").value;
                observaciones=document.getElementById("observaciones").value;
                cliente=document.getElementById("cliente").value;
                telefono=document.getElementById("telefono").value;
                correo=document.getElementById("correo").value;
                plan=document.getElementById("plan").value;
                equipo=document.getElementById("equipo").value;
                renta=document.getElementById("renta").value;
                
                var xmlhttp = new XMLHttpRequest();
                xmlhttp.open("POST", "/orden_update", true);
                xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xmlhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                         //console.log("Actualizacion Exitosa");
                         document.location.reload();
                         //console.log(this.responseText);
                        }
                }; 
                xmlhttp.onerror = function () {
                  alert("** Un error ha ocurrido en la actualizacion");
                };
                parametros="_token="+"{{csrf_token()}}"
                parametros+="&id="+id;
                parametros+="&estatus_final="+estatus_final;
                parametros+="&observaciones="+observaciones;
                parametros+="&cliente="+cliente;
                parametros+="&telefono="+telefono;
                parametros+="&correo="+correo;
                parametros+="&plan="+plan;
                parametros+="&equipo="+equipo;
                parametros+="&renta="+renta;
                
                xmlhttp.send(parametros);
            }
        </script>



</x-app-layout>
