<x-app-layout>
    <x-slot name="header">
            {{ __('Generacion Demanda - Nuevo') }}
    </x-slot>
    <form method="post" action="{{route('demanda_nuevo')}}">
    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Registo Dia de Generacion de Demanda</div>
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
                <div class="w-1/4">
                    <span class="text-xs">SMS Masivos</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="sms" value="{{old('sms')}}">
                    
                    @error('sms')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>           
                <div class="w-1/4">
                    <span class="text-xs">SMS Individuales</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="sms_individual" value="{{old('sms_individual')}}">
                    
                    @error('sms_individual')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                                
                <div class="w-1/4">
                    <span class="text-xs">Llamadas</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="llamadas" value="{{old('llamadas')}}">
                    
                    @error('llamadas')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                                
                <div class="w-1/4">
                    <span class="text-xs">Redes Sociales</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="rs" value="{{old('rs')}}">
                    
                    @error('rs')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                                
            </div> 
            <div class="w-full text-base font-semibold space-x-2 py-3">
                Depuracion de base de contactos
            </div>  
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Minutos por limpieza de base</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="minutos_base" value="{{old('minutos_base')}}">                    
                    @error('minutos_base')
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
