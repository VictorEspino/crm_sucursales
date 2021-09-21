<x-app-layout>
    <x-slot name="header">
            {{ __('Registro de Incidencia - Nuevo') }}
    </x-slot>
    <form method="post" action="{{route('incidencia_nuevo')}}">
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Registo de Incidencia</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>                        
        </div> <!--FIN ENCABEZADO-->
        
            @csrf
        
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/3">
                    <span class="text-xs">Dia de Incidencia</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="dia_incidencia" value="{{old('dia_incidencia')}}" placeholder="YYYY-MM-DD">
                    
                    @error('dia_incidencia')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                                
                <div class="w-2/3">
                    <span class="text-xs">Tipo Incidencia</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="tipo">
                        <option value="" class=""></option>                        
                        <option value="Incapacidad" class="" {{old('tipo')=="Incapacidad"?'selected':''}}>Incapacidad</option>
                        <option value="Permiso" class="" {{old('tipo')=="Permiso"?'selected':''}}>Permiso</option>
                        <option value="Vacaciones" class="" {{old('tipo')=="Vacaciones"?'selected':''}}>Vacaciones</option>
                        <option value="Descanso" class="" {{old('tipo')=="Descanso"?'selected':''}}>Descanso</option>
                    </select>    
                    @error('tipo')
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
    </div>
</form>    

</x-app-layout>
