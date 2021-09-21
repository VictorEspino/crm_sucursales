<x-app-layout>
    <x-slot name="header">
            {{ __('Objetivos - Actualizacion') }}
    </x-slot>

    <div class="flex flex-col w-full bg-white text-gray-700 shadow-lg rounded-lg">
        <div class="w-full rounded-t-lg bg-gray-200 p-3 flex flex-col border-b border-gray-800"> <!--ENCABEZADO-->
            <div class="w-full text-lg font-semibold">Actulizacion de Objetivos</div>
            <div class="w-full text-sm">({{Auth::user()->udn}}) - {{Auth::user()->pdv}}</div>
            <div class="w-full text-sm">({{Auth::user()->empleado}}) - {{Auth::user()->name}}</div>            
        </div> <!--FIN ENCABEZADO-->
        <form method="post" action="{{route('objetivo_update')}}">
            @csrf
        <div class="w-full rounded-b-lg bg-white p-3 flex flex-col"> <!--CONTENIDO-->
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Sucursal</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="sucursal" id="sucursal" onChange="buscar_objetivo()">
                        <option value="" class=""></option>                        
                    <?php  
                        $sucursales=App\Models\Sucursal::orderBy('pdv','asc')->get();
                        foreach ($sucursales as $sucursal) {
                            if((Auth::user()->puesto=="Gerente" && Auth::user()->udn==$sucursal->udn) || (Auth::user()->puesto=="Regional" && $sucursal->region==Auth::user()->region))
                                {
                    ?>
                        <option value="{{$sucursal->udn}}" class="" {{old('sucursal')==$sucursal->udn?'selected':''}}>{{$sucursal->pdv}}</option>
                    <?php
                                }
                        }
                    ?>
                    </select>
                    @error('sucursal')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Periodo</span><br>
                    <select class="w-full rounded p-1 border border-gray-300" type="text" name="periodo" id="periodo" onChange="buscar_objetivo()">
                        <option value="" class=""></option>
                        <option value="2021-09" class="" {{old('periodo')=="2021-09"?'selected':''}}>2021-09</option>
                    </select> 
                    @error('periodo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full pt-2"><span class="text-sm font-bold">Cuota Mensual:</span></div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Activaciones CON equipo</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_con" id="a_con" value="{{old('a_con')}}">
                    @error('a_con')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Activaciones SIN equipo</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_sin" id="a_sin" value="{{old('a_sin')}}">
                    @error('a_sin')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones CON equipo</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_con" id="r_con" value="{{old('r_con')}}">
                    @error('r_con')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones SIN equipo</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_sin" id="r_sin" value="{{old('r_sin')}}">
                    @error('a_sin')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                
            </div>
            <div class="w-full pt-2"><span class="text-sm font-bold">Minutos:</span></div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Minutos esperados de productividad (Diario)</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="min_diario" id="min_diario" value="{{old('min_diario')}}">
                    @error('min_diario')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div> 
            </div>
            <div class="w-full pt-2"><span class="text-sm font-bold">Ejecutivos:</span></div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Plantilla autorizada de ejecutivos de venta</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="ejecutivos" id="ejecutivos" value="{{old('ejecutivos')}}">
                    @error('ejecutivos')
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
function buscar_objetivo() {
    
    var periodo=document.getElementById("periodo").value;
    var sucursal=document.getElementById("sucursal").value;
    if(periodo=='' || sucursal=='')
    {
        //alert("nada que consultar");
        return(0);
    }
    //alert("consultando");
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        //document.getElementById("txtHint").innerHTML = this.responseText;
        if(this.responseText!='')
            {
                respuesta=JSON.parse(this.response);
                //console.log(respuesta);
                document.getElementById("a_con").value="";
                document.getElementById("a_sin").value="";
                document.getElementById("r_con").value="";
                document.getElementById("r_sin").value="";
                document.getElementById("ejecutivos").value="";
                document.getElementById("min_diario").value="";

                document.getElementById("a_con").value=respuesta.ac;
                document.getElementById("a_sin").value=respuesta.asi;
                document.getElementById("r_con").value=respuesta.rc;
                document.getElementById("r_sin").value=respuesta.rs;
                document.getElementById("ejecutivos").value=respuesta.ejecutivos;
                document.getElementById("min_diario").value=respuesta.min_diario;

                
            }
        else
            {
                document.getElementById("a_con").value="";
                document.getElementById("a_sin").value="";
                document.getElementById("r_con").value="";
                document.getElementById("r_sin").value="";
                document.getElementById("ejecutivos").value="";
                document.getElementById("min_diario").value="";
            }
        
      }
    };
    xmlhttp.open("GET", "/objetivo_consulta/" + periodo + "/" + sucursal, true);
    xmlhttp.send();
    
}
</script>
</x-app-layout>
