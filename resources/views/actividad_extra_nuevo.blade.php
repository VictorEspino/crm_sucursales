<x-app-layout>
    <x-slot name="header">
            {{ __('Actividad - Nuevo') }}
    </x-slot>
    <form method="post" action="{{route('actividad_extra_nuevo')}}">
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Registo de actividad</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>                        
        </div> <!--FIN ENCABEZADO-->
        
            @csrf
        
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/3">
                    <span class="text-xs">Dia de Trabajo</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="date" name="dia_trabajo" value="{{old('dia_trabajo')}}" placeholder="YYYY-MM-DD">
                    
                    @error('dia_trabajo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                                
            </div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/2">
                    <span class="text-xs">Tipo de Actividad</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" name="tipo">
                        <option value="" class=""></option>                        
                        <option value="Visita a cliente" class="" {{old('tipo')=="Visita a cliente"?'selected':''}}>Visita a cliente</option>
                        <option value="Hostess" class="" {{old('tipo')=="Hostess"?'selected':''}}>Hostess</option>
                        <option value="Capacitacion" class="" {{old('tipo')=="Capacitacion"?'selected':''}}>Capacitacion</option>
                    </select>
                    
                    @error('tipo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>           
                <div class="w-1/2">
                    <span class="text-xs">Minutos de actividad</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="minutos" value="{{old('minutos')}}">
                    
                    @error('minutos')
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
