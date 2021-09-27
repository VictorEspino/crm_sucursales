<x-app-layout>
    <x-slot name="header">
            {{ __('Funnel - Nuevo') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Registo Funnel</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>            
        </div> <!--FIN ENCABEZADO-->
        <form method="post" action="{{route('funnel_nuevo')}}">
            @csrf
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/3">
                    <span class="text-xs">Origen del Prospecto</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="origen">
                        <option value="" class=""></option>                        
                        <option value="Llamada" class="" {{old('origen')=="Llamada"?'selected':''}}>Llamada</option>
                        <option value="SMS" class="" {{old('origen')=="SMS"?'selected':''}}>SMS</option>
                        <option value="Redes Sociales" class="" {{old('origen')=="Redes Sociales"?'selected':''}}>Redes Sociales</option>
                    </select>    
                    @error('origen')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-2/3">
                    <span class="text-xs">Nombre Prospecto</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="funnel_nombre" value="{{old('funnel_nombre')}}">
                    @error('funnel_nombre')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/2">
                    <span class="text-xs">Telefono Prospecto</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="funnel_telefono" value="{{old('funnel_telefono')}}">
                    @error('funnel_telefono')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-2/3">
                    <span class="text-xs">Email Prospecto</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="funnel_correo" value="{{old('funnel_correo')}}">
                    @error('funnel_correo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/3">
                    <span class="text-xs">Producto Interes</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="tipo">
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
                <div class="w-1/3">
                    <span class="text-xs">Plan Interes</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="funnel_plan" value="{{old('funnel_plan')}}">
                    @error('funnel_plan')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/3">
                    <span class="text-xs">Equipo Interes</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="funnel_equipo" value="{{old('funnel_equipo')}}">
                    @error('funnel_equipo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/2">
                    <span class="text-xs">Estatus</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="funnel_estatus" value="Registro nuevo" readonly>
                    @error('funnel_estatus')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/2">
                    <span class="text-xs">Fecha Siguiente Contacto</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="fecha_sig_contacto" value="{{old('fecha_sig_contacto')}}" placeholder='YYYY-MM-DD'>
                    @error('fecha_sig_contacto')
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
        </div> <!--FIN CONTENIDO-->
        <div class="w-full flex justify-center py-4">
            <button class="rounded p-1 border bg-green-500 hover:bg-green-700 text-gray-100 font-semibold">Guardar</button>
        </div>
        </form>
    </div>
</x-app-layout>
