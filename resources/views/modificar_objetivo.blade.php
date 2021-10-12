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
                        @foreach ($periodos as $periodo)
                            <option value="{{$periodo->periodo}}" class="" {{old('periodo')==$periodo->periodo?'selected':''}}>{{$periodo->periodo}}</option>    
                        @endforeach
                        
                        
                    </select> 
                    @error('periodo')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
            </div>
            <div class="w-full pt-2"><span class="text-sm font-bold">Cuota Primera Quincena:</span></div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Activaciones CON equipo Q1</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_con_q1" id="a_con_q1" value="{{old('a_con_q1')}}" onChange="update_cuotas()">
                    @error('a_con_q1')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Activaciones SIN equipo Q1</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_sin_q1" id="a_sin_q1" value="{{old('a_sin_q1')}}" onChange="update_cuotas()">
                    @error('a_sin_q1')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones CON equipo Q1</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_con_q1" id="r_con_q1" value="{{old('r_con_q1')}}" onChange="update_cuotas()">
                    @error('r_con_q1')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones SIN equipo Q1</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_sin_q1" id="r_sin_q1" value="{{old('r_sin_q1')}}" onChange="update_cuotas()">
                    @error('a_sin_q1')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                
            </div>
            <div class="w-full pt-2"><span class="text-sm font-bold">Cuota Segunda Quincena:</span></div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Activaciones CON equipo Q2</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_con_q2" id="a_con_q2" value="{{old('a_con_q2')}}" onChange="update_cuotas()">
                    @error('a_con_q2')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Activaciones SIN equipo Q2</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_sin_q2" id="a_sin_q2" value="{{old('a_sin_q2')}}" onChange="update_cuotas()">
                    @error('a_sin_q2')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones CON equipo Q2</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_con_q2" id="r_con_q2" value="{{old('r_con_q2')}}" onChange="update_cuotas()">
                    @error('r_con_q2')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones SIN equipo Q2</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_sin_q2" id="r_sin_q2" value="{{old('r_sin_q2')}}" onChange="update_cuotas()">
                    @error('a_sin_q2')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>                
            </div>
            <div class="w-full pt-2"><span class="text-sm font-bold text-red-700">Cuota Mensual:</span></div>
            <div class="w-full flex flex-row space-x-2">
                <div class="w-1/4">
                    <span class="text-xs">Activaciones CON equipo Mensual</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_con" id="a_con" value="{{old('a_con')}}" readonly>
                    @error('a_con')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Activaciones SIN equipo Mensual</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="a_sin" id="a_sin" value="{{old('a_sin')}}" readonly>
                    @error('a_sin')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones CON equipo Mensual</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_con" id="r_con" value="{{old('r_con')}}" readonly>
                    @error('r_con')
                      <br><span class="text-xs italic text-red-700 text-xs">{{ $message }}</span>
                    @enderror                    
                </div>
                <div class="w-1/4">
                    <span class="text-xs">Renovaciones SIN equipo Mensual</span><br>
                    <input class="w-full rounded p-1 border border-gray-300" type="text" name="r_sin" id="r_sin" value="{{old('r_sin')}}" readonly>
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
function update_cuotas()
{
    ac_1=parseInt(document.getElementById("a_con_q1").value);
    as_1=parseInt(document.getElementById("a_sin_q1").value);
    rc_1=parseInt(document.getElementById("r_con_q1").value);
    rs_1=parseInt(document.getElementById("r_sin_q1").value);
    ac_2=parseInt(document.getElementById("a_con_q2").value);
    as_2=parseInt(document.getElementById("a_sin_q2").value);
    rc_2=parseInt(document.getElementById("r_con_q2").value);
    rs_2=parseInt(document.getElementById("r_sin_q2").value);
    document.getElementById("a_con").value=ac_1+ac_2;
    document.getElementById("a_sin").value=as_1+as_2;
    document.getElementById("r_con").value=rc_1+rc_2;
    document.getElementById("r_sin").value=rs_1+rs_2;
}
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

                document.getElementById("a_con_q1").value="";
                document.getElementById("a_sin_q1").value="";
                document.getElementById("r_con_q1").value="";
                document.getElementById("r_sin_q1").value="";

                document.getElementById("a_con_q2").value="";
                document.getElementById("a_sin_q2").value="";
                document.getElementById("r_con_q2").value="";
                document.getElementById("r_sin_q2").value="";

                document.getElementById("ejecutivos").value="";
                document.getElementById("min_diario").value="";

                document.getElementById("a_con").value=respuesta.ac;
                document.getElementById("a_sin").value=respuesta.asi;
                document.getElementById("r_con").value=respuesta.rc;
                document.getElementById("r_sin").value=respuesta.rs;

                document.getElementById("a_con_q1").value=respuesta.ac_q1;
                document.getElementById("a_sin_q1").value=respuesta.as_q1;
                document.getElementById("r_con_q1").value=respuesta.rc_q1;
                document.getElementById("r_sin_q1").value=respuesta.rs_q1;

                document.getElementById("a_con_q2").value=respuesta.ac_q2;
                document.getElementById("a_sin_q2").value=respuesta.as_q2;
                document.getElementById("r_con_q2").value=respuesta.rc_q2;
                document.getElementById("r_sin_q2").value=respuesta.rs_q2;


                document.getElementById("ejecutivos").value=respuesta.ejecutivos;
                document.getElementById("min_diario").value=respuesta.min_diario;

                
            }
        else
            {
                document.getElementById("a_con").value="";
                document.getElementById("a_sin").value="";
                document.getElementById("r_con").value="";
                document.getElementById("r_sin").value="";

                document.getElementById("a_con_q1").value="";
                document.getElementById("a_sin_q1").value="";
                document.getElementById("r_con_q1").value="";
                document.getElementById("r_sin_q1").value="";

                document.getElementById("a_con_q2").value="";
                document.getElementById("a_sin_q2").value="";
                document.getElementById("r_con_q2").value="";
                document.getElementById("r_sin_q2").value="";

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
