<x-app-layout>
    <x-slot name="header">
            {{ __('Orden - Nuevo') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Registo de Orden</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>            
        </div> <!--FIN ENCABEZADO-->
        <form method="post" action="{{route('orden_nuevo')}}">
            @csrf
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/3">
                    <span class="text-xs">Origen de Contacto</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="origen" id="origen" onChange="evaluaOrigen()">
                        <option value="" class=""></option>                        
                        <option value="Llamada" class="" {{old('origen')=="Llamada"?'selected':''}}>Llamada</option>
                        <option value="SMS" class="" {{old('origen')=="SMS"?'selected':''}}>SMS</option>
                        <option value="Redes Sociales" class="" {{old('origen')=="Redes Sociales"?'selected':''}}>Redes Sociales</option>
                        <?php
                        if(isset($_GET['ff']))
                        {
                        ?>
                        <option value="Tienda" class="" {{old('origen')=="Tienda"?'selected':''}}>Tienda</option>
                        <?php
                        }
                        ?>
                    </select>    
                    @error('origen')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-2/3">
                    <span class="text-xs">Nombre Cliente</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="orden_nombre" value="{{old('orden_nombre')}}" id="cliente">
                    @error('orden_nombre')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/2">
                    <span class="text-xs">Telefono Cliente</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="orden_telefono" value="{{old('orden_telefono')}}" id="telefono">
                    @error('orden_telefono')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-2/3">
                    <span class="text-xs">Email</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="orden_correo" value="{{old('orden_correo')}}" id="correo">
                    @error('orden_correo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/3">
                    <span class="text-xs">Fecha Nacimiento</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="date" name="f_nacimiento" id="f_nacimiento" value="{{old('f_nacimiento')}}" placeholder="YYYY-MM-DD" onchange='calcula_edad()'>
                    @error('f_nacimiento')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/3">
                    <span class="text-xs">Edad</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" id="edad" name="edad" value="{{old('edad')}}" readonly>                    
                    @error('edad')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                
                <div class="w-1/3">
                    <span class="text-xs">Genero</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="genero">
                        <option value="" class=""></option>                        
                        <option value="Femenino" class="" {{old('genero')=="Femenino"?'selected':''}}>Femenino</option>
                        <option value="Masculino" class="" {{old('genero')=="Masculino"?'selected':''}}>Masculino</option>
                        <option value="Otro" class="" {{old('genero')=="Otro"?'selected':''}}>Otro</option>
                    </select>    
                    @error('genero')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Producto</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="tipo" id="producto">
                        <option value="" class=""></option>                        
                    <?php  
                        $tipos=App\Models\TipoMovimiento::all();
                        foreach ($tipos as $tipo) {
                    ?>
                        <option value="{{$tipo->tipo}}" class="" {{old('tipo')==$tipo->tipo?'selected':''}}>{{$tipo->tipo}}</option>
                    <?php
                        }
                    ?>
                    </select> 
                    @error('tipo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Plan</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="orden_plan" value="{{old('orden_plan')}}" id="plan">
                    @error('orden_plan')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Equipo</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="orden_equipo" value="{{old('orden_equipo')}}" id="equipo">
                    @error('orden_equipo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renta</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="orden_renta" value="{{old('orden_renta')}}">
                    @error('orden_renta')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/3">
                    <span class="text-xs">Estatus Final AT&T</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="estatus_final">
                        <option value="" class=""></option>                        
                    <?php  
                        $estatuss=App\Models\CatalogoEstatus::all();
                        foreach ($estatuss as $estatus) {
                    ?>
                        <option value="{{$estatus->estatus}}" class="" {{old('estatus_final')==$estatus->estatus?'selected':''}}>{{$estatus->estatus}}</option>
                    <?php
                        }
                    ?>
                    </select> 
                    @error('estatus_final')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                  
                </div>
                <div class="w-1/3">
                    <span class="text-xs">Numero Orden</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="numero_orden" value="{{old('numero_orden')}}">
                    @error('numero_orden')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/3">
                    <span class="text-xs">Flujo</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="flujo">
                        <option value="" class=""></option>                        
                        <option value="Efectivo" class="" {{old('flujo')=="Efectivo"?'selected':''}}>Efectivo</option>
                        <option value="TDC" class="" {{old('flujo')=="TDC"?'selected':''}}>TDC</option>
                    </select>    
                    @error('flujo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Porcentaje Requerido</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="porcentaje_requerido" value="{{old('porcentaje_requerido')}}"> 
                    @error('porcentaje_requerido')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Monto Total</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="monto_total" value="{{old('monto_total')}}">
                    @error('monto_total')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Generada en</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="generada_en">
                        <option value="Tienda" class="" {{old('generada_en')=="Tienda"?'selected':''}}>Tienda</option>
                        <option value="Virtual MAQ" class="" {{old('generada_en')=="Virtual MAQ"?'selected':''}}>Virtual MAQ</option>
                    </select>    
                    @error('generada_en')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Riesgo</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="riesgo">
                        <option value=""></option>
                        <option value="HH" class="" {{old('riesgo')=="HH"?'selected':''}}>HH</option>
                        <option value="HM" class="" {{old('riesgo')=="HM"?'selected':''}}>HM</option>
                        <option value="HL" class="" {{old('riesgo')=="HL"?'selected':''}}>HL</option>
                        <option value="MH" class="" {{old('riesgo')=="MH"?'selected':''}}>MH</option>
                        <option value="MM" class="" {{old('riesgo')=="MM"?'selected':''}}>MM</option>
                        <option value="ML" class="" {{old('riesgo')=="ML"?'selected':''}}>ML</option>
                        <option value="LH" class="" {{old('riesgo')=="MH"?'selected':''}}>LH</option>
                        <option value="LM" class="" {{old('riesgo')=="MM"?'selected':''}}>LM</option>
                        <option value="LL" class="" {{old('riesgo')=="ML"?'selected':''}}>LL</option>
                        <option value="Sin riesgo" class="" {{old('riesgo')=="Sin riesgo"?'selected':''}}>Sin riesgo</option>
                    </select>    
                    @error('riesgo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-full">
                    <span class="text-xs">Observaciones</span><br>
                    <textarea class="w-full rounded p-1 border border-gray-300" name="observaciones">{{old('observaciones')}}</textarea>
                    @error('observaciones')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div id="interaccion_origen" class="hidden w-full flex flex-row space-x-2">
                <div class="w-full">
                    <span class="text-xs">Interaccion Origen</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="interaccion_origen">
                        <option value="" class=""></option>                        
                    <?php  
                        $tramites=App\Models\CatalogoInteracciones::all();
                        foreach ($tramites as $tramite) {
                    ?>
                        <option value="{{$tramite->tramite}}" class="" {{old('interaccion_origen')==$tramite->tramite?'selected':''}}>{{$tramite->tramite}}</option>
                    <?php
                        }
                    ?>
                    </select> 
                    @error('interaccion_origen')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
        </div> <!--FIN CONTENIDO-->
        <div class="w-full flex justify-center py-4">
            <button class="rounded p-1 border bg-green-500 hover:bg-green-700 text-gray-100 font-semibold">Guardar</button>
        </div>
        </form>
    </div>
    <script>
        function calcula_edad()
        {
            var enteredDate = document.getElementById('f_nacimiento').value;
            var years = new Date(new Date() - new Date(enteredDate)).getFullYear() - 1970;
            document.getElementById('edad').value=years;
        }
    </script>
<?php
    if(isset($_GET['ff']))
    {
?>
    <script>
        document.getElementById("origen").value="{{$_GET['origen']}}";
        document.getElementById("cliente").value="{{$_GET['cliente']}}";
        document.getElementById("telefono").value="{{$_GET['telefono']}}";
        document.getElementById("correo").value="{{$_GET['correo']}}";
        document.getElementById("producto").value="{{$_GET['producto']}}";
        document.getElementById("plan").value="{{$_GET['plan']}}";
        document.getElementById("equipo").value="{{$_GET['equipo']}}";
    </script>
<?php        
    }
?>
    <script>
        <?php if(old('origen')=="Tienda")
                {
        ?>
            document.getElementById("interaccion_origen").style.display="block";
        <?php
                }
        ?>
        function evaluaOrigen()
        {
            origen=document.getElementById("origen").value;
            if(origen=="Tienda")
            {
                document.getElementById("interaccion_origen").style.display="block";
            }
            else
            {
                document.getElementById("interaccion_origen").style.display="none";
            }
        }
    </script>
</x-app-layout>
