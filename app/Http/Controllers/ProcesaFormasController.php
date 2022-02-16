<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Sucursal;
use App\Models\Ordenes;
use App\Models\Funnel;
use App\Models\Objetivo;
use App\Models\Interaccion;
use App\Models\CatalogoInteracciones;
use App\Models\GeneracionDemanda;
use App\Models\ParametrosTiempo;
use App\Models\Incidencia;
use App\Models\TimeUpdate;
use App\Models\ActividadesExtra;

use Illuminate\Http\Request;

class ProcesaFormasController extends Controller
{
    
    public function plantilla_nuevo(Request $request)
    {
        $request->validate([
            'empleado' => 'required|unique:users|numeric',
            'nombre' => 'required',
            'f_ingreso'=>'required|date_format:Y-m-d',
            'estatus'=>'required',
            'puesto' => 'required',
            'sucursal' => 'required',
            'email' => 'email|required|unique:users',
        ]);
        $usuario=new User;      
        $usuario->empleado=$request->empleado;
        $usuario->name=$request->nombre;
        $usuario->puesto=$request->puesto;
        $usuario->udn=$request->sucursal;
        $usuario->email=$request->email;
        $sucursal=Sucursal::where('udn',$request->sucursal)->get()->first();
        $usuario->pdv=$sucursal->pdv;
        $usuario->region=$sucursal->region;
        $usuario->password=Hash::make('bca');
        $usuario->f_ingreso=$request->f_ingreso;
        $usuario->estatus=$request->estatus;
        $usuario->attuid=$request->boolean('attuid');
        $usuario->vpn=$request->boolean('vpn');
        $usuario->avs=$request->boolean('avs');
        $usuario->pb=$request->boolean('pb');
        $usuario->noe=$request->boolean('noe');
        $usuario->asd=$request->boolean('asd');
        $usuario->save();
        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'El registro del empleado ('.$request->empleado.' - '.$request->nombre.') se realizo de manera exitosa!'
                              ]));
    }
    public function plantilla_update(Request $request)
    {
        $request->validate([
            'empleado' => 'required',
            'nombre' => 'required',
            'f_ingreso'=>'required|date_format:Y-m-d',
            'estatus'=>'required',
            'puesto' => 'required',
            'sucursal' => 'required',
            'email' => 'email|required',
        ]);
        $sucursal=Sucursal::where('udn',$request->sucursal)->get()->first();
        User::where('empleado', $request->empleado)
        ->update(['name' => $request->nombre,
                  'puesto' => $request->puesto,
                  'email' => $request->email,
                  'udn' => $request->sucursal,
                  'pdv' => $sucursal->pdv,
                  'region' => $sucursal->region,
                  'f_ingreso'=>$request->f_ingreso,
                  'estatus'=>$request->estatus,
                  'attuid'=>$request->boolean('attuid'),
                  'avs'=>$request->boolean('avs'),
                  'vpn'=>$request->boolean('vpn'),
                  'pb'=>$request->boolean('pb'),
                  'noe'=>$request->boolean('noe'),
                  'asd'=>$request->boolean('asd'),
                ]);

        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'La actualizacion del empleado ('.$request->empleado.' - '.$request->nombre.') se realizo de manera exitosa!'
                              ]));
    }
    public function plantilla_consulta(Request $request) 
    {
        $empleado=$request->empleado;
        $registro=User::where('empleado',$empleado)->get()->first();
        return($registro);
    }

    public function interaccion_nuevo(Request $request)
    {
        $request->validate([
            'tramite' => 'required',
            'fin_interaccion' => 'required',
            'telefono_cliente' => 'required|numeric',
            'observaciones' => 'max:255',
        ]);
        $adicional="";
        if($request->fin_interaccion=="Funnel")
        {
            $request->validate([
                'origen' => 'required',
                'funnel_nombre' => 'required',
                'funnel_telefono' => 'required',
                'funnel_correo' => 'required|email',
                'tipo' => 'required',
                'funnel_plan' => 'required',
                'funnel_equipo' => 'required',
                'funnel_estatus' => 'required',
                'observaciones_f' => 'max:255',
                'fecha_sig_contacto' => 'required|date_format:Y-m-d'
            ]);
            $funnel=new Funnel;      
            $funnel->empleado=Auth::user()->empleado;
            $funnel->nombre=Auth::user()->name;
            $funnel->udn=Auth::user()->udn;
            $funnel->pdv=Auth::user()->pdv;
            $funnel->region=Auth::user()->region;
            $funnel->origen=$request->origen;
            $funnel->cliente=$request->funnel_nombre;
            $funnel->telefono=$request->funnel_telefono;
            $funnel->correo=$request->funnel_correo;
            $funnel->producto=$request->tipo;
            $funnel->plan=$request->funnel_plan;
            $funnel->equipo=$request->funnel_equipo;
            $funnel->estatus=$request->funnel_estatus;
            $funnel->observaciones=$request->observaciones_f;
            $funnel->fecha_sig_contacto=$request->fecha_sig_contacto;
            $funnel->minutos=10;
            $funnel->save();
            $adicional=" incluido el registro de prospecto ";
            //return(view('mensaje',[ 'estatus'=>'OK',
            //                        'mensaje'=>'El registro del prospecto ('.$request->funnel_nombre.') se realizo de manera exitosa!'
            //                      ]));
        }
        if($request->fin_interaccion=="Orden")
        {
            $request->validate([
                'origen' => 'required',
                'orden_nombre'=> 'required',
                'orden_telefono'=> 'required',
                'orden_correo'=> 'required',
                'edad'=> 'numeric',
                'f_nacimiento'=> 'date_format:Y-m-d',            
                'tipo_o'=> 'required',
                'orden_plan'=> 'required',
                'orden_renta'=> 'required|numeric',
                'estatus_final'=> 'required',
                'numero_orden'=> 'required',
                'flujo'=> 'required',
                'porcentaje_requerido'=> 'required',
                'monto_total'=> 'required',
                'riesgo'=> 'required',
                'observaciones_o'=> 'max:255'
            ]);
            $orden=new Ordenes;      
            $orden->empleado=Auth::user()->empleado;
            $orden->nombre=Auth::user()->name;
            $orden->udn=Auth::user()->udn;
            $orden->pdv=Auth::user()->pdv;
            $orden->region=Auth::user()->region;
            $orden->origen=$request->origen;
            $orden->cliente=$request->orden_nombre;
            $orden->telefono=$request->orden_telefono;
            $orden->correo=$request->orden_correo;
            $orden->producto=$request->tipo_o;
            $orden->plan=$request->orden_plan;
            $orden->renta=$request->orden_renta;
            $orden->equipo=$request->orden_equipo;
            $orden->estatus_final=$request->estatus_final;
            $orden->observaciones=$request->observaciones_o;
            $orden->edad=$request->edad;
            $orden->f_nacimiento=$request->f_nacimiento;
            $orden->genero=$request->genero;
            $orden->numero_orden=$request->numero_orden;
            $orden->porcentaje_requerido=$request->porcentaje_requerido;
            $orden->monto_total=$request->monto_total;
            $orden->generada_en=$request->generada_en;
            $orden->riesgo=$request->riesgo;
            $orden->flujo=$request->flujo;
            $minutos=20; //default value
            $tiempos=ParametrosTiempo::where('fuente','ORDENES')
                                ->where('tipo',$request->estatus_final)
                                ->get();
        
            foreach($tiempos as $tiempo)
            {
                $minutos=$tiempo->minutos;
            }
            $orden->minutos=$minutos;
            $orden->save();
            $adicional=" incluido el registro la orden ";
        }
        $interaccion=new Interaccion;      
        $interaccion->empleado=Auth::user()->empleado;
        $interaccion->nombre=Auth::user()->name;
        $interaccion->udn=Auth::user()->udn;
        $interaccion->pdv=Auth::user()->pdv;
        $interaccion->region=Auth::user()->region;
        $interaccion->tramite=$request->tramite;
        $interaccion->fin_interaccion=$request->fin_interaccion;
        $interaccion->telefono=$request->telefono_cliente;
        $interaccion->observaciones=$request->observaciones;
        $intencion=CatalogoInteracciones::where('tramite',$request->tramite)->get()->first();
        $interaccion->intencion=$intencion->intencion;

        $minutos=4; //default value
        $tiempos=ParametrosTiempo::where('fuente','INTERACCION')
                                ->where('tipo',$request->tramite)
                                ->get();
        
        foreach($tiempos as $tiempo)
        {
            $minutos=$tiempo->minutos;
        }
        $interaccion->minutos=$minutos;

        $interaccion->save();
        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'El registro de la interaccion,'.$adicional.'en sucursal ('.$request->tramite.') se realizo de manera exitosa!'
                              ]));
    }
    public function funnel_nuevo(Request $request)
    {
        $request->validate([
            'origen' => 'required',
            'funnel_nombre' => 'required',
            'funnel_telefono' => 'required',
            'funnel_correo' => 'required|email',
            'tipo' => 'required',
            'funnel_plan' => 'required',
            'funnel_equipo' => 'required',
            'funnel_estatus' => 'required',
            'observaciones' => 'max:255',
            'fecha_sig_contacto' => 'required|date_format:Y-m-d'
        ]);
        $funnel=new Funnel;      
        $funnel->empleado=Auth::user()->empleado;
        $funnel->nombre=Auth::user()->name;
        $funnel->udn=Auth::user()->udn;
        $funnel->pdv=Auth::user()->pdv;
        $funnel->region=Auth::user()->region;
        $funnel->origen=$request->origen;
        $funnel->cliente=$request->funnel_nombre;
        $funnel->telefono=$request->funnel_telefono;
        $funnel->correo=$request->funnel_correo;
        $funnel->producto=$request->tipo;
        $funnel->plan=$request->funnel_plan;
        $funnel->equipo=$request->funnel_equipo;
        $funnel->estatus=$request->funnel_estatus;
        $funnel->observaciones=$request->observaciones;
        $funnel->fecha_sig_contacto=$request->fecha_sig_contacto;
        $funnel->minutos=10;
        $funnel->save();
        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'El registro del prospecto ('.$request->funnel_nombre.') se realizo de manera exitosa!'
                              ]));
    }
    public function orden_nuevo(Request $request)
    {   
        $request->validate([
            'origen' => 'required',
            'orden_nombre'=> 'required',
            'orden_telefono'=> 'required',
            'orden_correo'=> 'required',
            'edad'=> 'numeric',
            'f_nacimiento'=> 'date_format:Y-m-d',            
            'tipo'=> 'required',
            'orden_plan'=> 'required',
            'orden_renta'=> 'required|numeric',
            'estatus_final'=> 'required',
            'numero_orden'=> 'required',
            'flujo'=> 'required',
            'porcentaje_requerido'=> 'required',
            'monto_total'=> 'required',
            'riesgo'=> 'required',
            'observaciones'=> 'max:255',
            'interaccion_origen'=>'exclude_unless:origen,Tienda|required'

        ]);
        $orden=new Ordenes;      
        $orden->empleado=Auth::user()->empleado;
        $orden->nombre=Auth::user()->name;
        $orden->udn=Auth::user()->udn;
        $orden->pdv=Auth::user()->pdv;
        $orden->region=Auth::user()->region;
        $orden->origen=$request->origen;
        $orden->cliente=$request->orden_nombre;
        $orden->telefono=$request->orden_telefono;
        $orden->correo=$request->orden_correo;
        $orden->producto=$request->tipo;
        $orden->plan=$request->orden_plan;
        $orden->renta=$request->orden_renta;
        $orden->equipo=$request->orden_equipo;
        $orden->estatus_final=$request->estatus_final;
        $orden->observaciones=$request->observaciones;
        $orden->edad=$request->edad;
        $orden->f_nacimiento=$request->f_nacimiento;
        $orden->genero=$request->genero;
        $orden->numero_orden=$request->numero_orden;
        $orden->porcentaje_requerido=$request->porcentaje_requerido;
        $orden->monto_total=$request->monto_total;
        $orden->generada_en=$request->generada_en;
        $orden->riesgo=$request->riesgo;
        $orden->flujo=$request->flujo;
        $minutos=20; //default value
        $tiempos=ParametrosTiempo::where('fuente','ORDENES')
                                ->where('tipo',$request->estatus_final)
                                ->get();
        
        foreach($tiempos as $tiempo)
        {
            $minutos=$tiempo->minutos;
        }
        $orden->minutos=$minutos;
        $orden->save();

        if($request->origen=="Tienda")
        {
            $interaccion=new Interaccion;
            $interaccion->tramite=$request->interaccion_origen;    
            $interaccion->empleado=Auth::user()->empleado;
            $interaccion->nombre=Auth::user()->name;
            $interaccion->udn=Auth::user()->udn;
            $interaccion->pdv=Auth::user()->pdv;
            $interaccion->region=Auth::user()->region;
            $interaccion->fin_interaccion="Orden";
            $interaccion->telefono=$request->orden_telefono;
            $interaccion->observaciones=$request->observaciones;
            $intencion=CatalogoInteracciones::where('tramite',$request->interaccion_origen)->get()->first();
            $interaccion->intencion=$intencion->intencion;

            $minutos=4; //default value
            $tiempos=ParametrosTiempo::where('fuente','INTERACCION')
                                    ->where('tipo',$request->tramite)
                                    ->get();
            
            foreach($tiempos as $tiempo)
            {
                $minutos=$tiempo->minutos;
            }
            $interaccion->minutos=$minutos;

            $interaccion->save();

        }
        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'El registro de la orden ('.$request->numero_orden.') se realizo de manera exitosa!'
                              ]));
    }
    public function funnel_detalles(Request $request)
    {
        return(Funnel::find($request->id));
    }
    public function funnel_update(Request $request)
    {
        $cambio=false;

        $funnel=Funnel::find($request->id);

        $e1_a=$funnel->estatus1;
        $e2_a=$funnel->estatus2;
        $e3_a=$funnel->estatus3;
        $fs_a=$funnel->fecha_sig_contacto;
        $e_a=$funnel->estatus;
        $o_a=$funnel->observaciones;

        if($e1_a!=$request->estatus1 || $e2_a!=$request->estatus2 || 
            $e3_a!=$request->estatus3 || $fs_a!=$request->fecha_sig_contacto ||
            $e_a!=$request->estatus || $o_a!=$request->observaciones)
            {
                $cambio=true;
            }



        $funnel->estatus1=$request->estatus1;
        $funnel->estatus2=$request->estatus2;
        $funnel->estatus3=$request->estatus3;
        $funnel->fecha_sig_contacto=$request->fecha_sig_contacto;
        $funnel->estatus=$request->estatus;
        $funnel->observaciones=$request->observaciones;
        $funnel->cliente=$request->cliente;
        $funnel->telefono=$request->telefono;
        $funnel->correo=$request->correo;
        $funnel->producto=$request->producto;
        $funnel->plan=$request->plan;
        $funnel->equipo=$request->equipo;
        $funnel->save();

        if($cambio)
        {
        $tiempos=new TimeUpdate();
        $tiempos->empleado=Auth::user()->empleado;
        $tiempos->nombre=Auth::user()->name;
        $tiempos->udn=Auth::user()->udn;
        $tiempos->pdv=Auth::user()->pdv;
        $tiempos->region=Auth::user()->region;
        $tiempos->minutos_funnel=10;
        $tiempos->funnel_id=$request->id;
        $tiempos->save();
        }


        return;
    }
    public function orden_detalles(Request $request)
    {
        return(Ordenes::find($request->id));
    }
    public function orden_update(Request $request)
    {
        $orden=Ordenes::find($request->id);
        $e_anterior=$orden->estatus_final;
        $orden->estatus_final=$request->estatus_final;
        $orden->observaciones=$request->observaciones;
        $orden->cliente=$request->cliente;
        $orden->telefono=$request->telefono;
        $orden->correo=$request->correo;
        $orden->plan=$request->plan;
        $orden->equipo=$request->equipo;
        $orden->renta=$request->renta;
        $orden->save();
        $minutos=0;
        if($e_anterior!=$request->estatus_final)
        {
            $tiempos_nuevo=ParametrosTiempo::where('fuente','ORDENES')
                                    ->where('tipo',$request->estatus_final)
                                    ->get();
            $tiempos_anterior=ParametrosTiempo::where('fuente','ORDENES')
                                    ->where('tipo',$e_anterior)
                                    ->get();

            foreach($tiempos_nuevo as $tiempo)
            {
                $minutos_nuevo=$tiempo->minutos;
            }

            foreach($tiempos_anterior as $tiempo)
            {
                $minutos_anterior=$tiempo->minutos;
            }
            
            //return($minutos_nuevo.'-'.$minutos_anterior);
        
            $tiempos=new TimeUpdate;
            $tiempos->empleado=Auth::user()->empleado;
            $tiempos->nombre=Auth::user()->name;
            $tiempos->udn=Auth::user()->udn;
            $tiempos->pdv=Auth::user()->pdv;
            $tiempos->region=Auth::user()->region;
            $tiempos->minutos_orden=intval($minutos_nuevo)-intval($minutos_anterior);
            $tiempos->orden_id=$request->id;
            $tiempos->save();
        }
        return;
    }
    public function demanda_nuevo(Request $request)
    {
        $request->validate([
            'dia_trabajo' => 'required|date_format:Y-m-d',
            'sms' => 'required|numeric',
            'sms_individual' => 'required|numeric',
            'llamadas' => 'required|numeric',
            'rs' => 'required|numeric',  
            'minutos_base'=> 'numeric|min:0'          
        ]);
        $registro=new GeneracionDemanda;      
        $registro->empleado=Auth::user()->empleado;
        $registro->nombre=Auth::user()->name;
        $registro->udn=Auth::user()->udn;
        $registro->pdv=Auth::user()->pdv;
        $registro->region=Auth::user()->region;
        $registro->dia_trabajo=$request->dia_trabajo;
        $minutos=0;
        $registro->sms=$request->sms;
        $registro->sms_individual=$request->sms_individual;
        $registro->llamadas=$request->llamadas;
        $registro->rs=$request->rs;
    
        $tiempos=ParametrosTiempo::where('fuente','DEMANDA')                                
                                ->get();
        
        foreach($tiempos as $tiempo)
        {
            if($tiempo->tipo=="sms")
            {
                if($request->sms!="0")
                {
                    $minutos=$minutos+$tiempo->minutos;
                }
            }    
            if($tiempo->tipo=="llamadas")
            {
                $minutos=$minutos+$request->llamadas*$tiempo->minutos;
            }    
            if($tiempo->tipo=="rs")
            {
                $minutos=$minutos+$request->rs*$tiempo->minutos;
            }    
            if($tiempo->tipo=="sms_individual")
            {
                $minutos=$minutos+$request->sms_individual*$tiempo->minutos;
            }   
        }
        $registro->minutos_base=$request->minutos_base;
        $registro->minutos=$minutos+$request->minutos_base;
        $registro->save();
        
        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'El registro del dia de trabajo ('.$request->dia_trabajo.' - '.$request->nombre.') se realizo de manera exitosa!'
                              ]));
    }
    public function incidencia_nuevo(Request $request)
    {
        $request->validate([
            'dia_incidencia' => 'required|date_format:Y-m-d',
            'tipo' => 'required',
            'observaciones' => 'max:255',            
        ]);

        $periodo_afectado=substr($request->dia_incidencia,0,7);

        $registro=new Incidencia;      
        $registro->empleado=Auth::user()->empleado;
        $registro->nombre=Auth::user()->name;
        $registro->udn=Auth::user()->udn;
        $registro->pdv=Auth::user()->pdv;
        $registro->region=Auth::user()->region;
        $registro->dia_incidencia=$request->dia_incidencia;
        $registro->tipo=$request->tipo;
        $minutos=360;
        if(Auth::user()->puesto!="Regional")
        {
            try{
                $sucursal_medida=Objetivo::where('udn',Auth::user()->udn)->where('periodo',substr($request->dia_incidencia,0,7))->get()->first();
                $minutos=$sucursal_medida->min_diario;
            }
            catch(\Exception $e)
            {
                $minutos=360;
                return(view('mensaje',[ 'estatus'=>'FAIL',
                                'mensaje'=>'No se puede registrar incidencia ('.$request->dia_incidencia.' - '.$request->tipo.'), es necesario primero registrar los objetivos de la sucursal para el periodo '.$periodo_afectado
                              ]));
            }
        }

        $registro->minutos=$minutos;
        $registro->save();
     
        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'El registro de la incidencia ('.$request->dia_incidencia.' - '.$request->tipo.') se realizo de manera exitosa!'
                              ]));
    }
    public function incidencia_borrar(Request $request)
    {
        Incidencia::find($request->id)->delete();
        return;
    }
    public function objetivo_consulta(Request $request) 
    {
        $periodo=$request->periodo;
        $udn=$request->udn;
        $registro=Objetivo::where('periodo',$periodo)->where('udn',$udn)->get()->first();
        return($registro);
    }
    public function objetivo_update(Request $request)
    {
        $request->validate([
            'periodo' => 'required',
            'sucursal' => 'required',
            'a_con_q1'=>'required|numeric',
            'a_sin_q1'=>'required|numeric',
            'r_con_q1' => 'required|numeric',
            'r_sin_q1' => 'required|numeric',
            'a_con_q2'=>'required|numeric',
            'a_sin_q2'=>'required|numeric',
            'r_con_q2' => 'required|numeric',
            'r_sin_q2' => 'required|numeric',
            'a_con'=>'required|numeric',
            'a_sin'=>'required|numeric',
            'r_con' => 'required|numeric',
            'r_sin' => 'required|numeric',
            'ejecutivos' => 'required|numeric',
            'min_diario' => 'required|numeric|max:600',

        ]);
        $actualizados=Objetivo::where('periodo', $request->periodo)->where('udn',$request->sucursal)
        ->update(['ac' => $request->a_con,
                  'asi' => $request->a_sin,
                  'rc' => $request->r_con,
                  'rs' => $request->r_sin,
                  'ac_q1' => $request->a_con_q1,
                  'as_q1' => $request->a_sin_q1,
                  'rc_q1' => $request->r_con_q1,
                  'rs_q1' => $request->r_sin_q1,
                  'ac_q2' => $request->a_con_q2,
                  'as_q2' => $request->a_sin_q2,
                  'rc_q2' => $request->r_con_q2,
                  'rs_q2' => $request->r_sin_q2,
                  'ejecutivos' => $request->ejecutivos,
                  'min_diario' => $request->min_diario,
                ]);

        if($actualizados==0)
        {
            $registro=new Objetivo;
            $registro->ac=$request->a_con;
            $registro->asi=$request->a_sin;
            $registro->rc=$request->r_con;
            $registro->rs=$request->r_sin;
            $registro->ac_q1=$request->a_con_q1;
            $registro->as_q1=$request->a_sin_q1;
            $registro->rc_q1=$request->r_con_q1;
            $registro->rs_q1=$request->r_sin_q1;
            $registro->ac_q2=$request->a_con_q2;
            $registro->as_q2=$request->a_sin_q2;
            $registro->rc_q2=$request->r_con_q2;
            $registro->rs_q2=$request->r_sin_q2;
            $registro->ejecutivos=$request->ejecutivos;
            $registro->min_diario=$request->min_diario;
            $registro->periodo=$request->periodo;
            $registro->udn=$request->sucursal;
            $sucursal=Sucursal::where('udn',$request->sucursal)->get()->first();
            $registro->pdv=$sucursal->pdv;
            $registro->region=$sucursal->region;
            $registro->save();

        }

        Incidencia::where('udn',$request->sucursal)
                    ->whereRaw('lpad(dia_incidencia,7,0)=?',[$request->periodo])
                    ->update(['minutos'=>$request->min_diario]);


        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'La actualizacion de objetivos ('.$request->periodo.') se realizo de manera exitosa!'
                              ]));
    }
    public function actividad_extra_nuevo(Request $request)
    {
        $request->validate([
            'dia_trabajo' => 'required|date_format:Y-m-d',
            'tipo' => 'required',
            'minutos' => 'required|numeric|min:0'
        
        ]);
        $registro=new ActividadesExtra;      
        $registro->empleado=Auth::user()->empleado;
        $registro->nombre=Auth::user()->name;
        $registro->udn=Auth::user()->udn;
        $registro->pdv=Auth::user()->pdv;
        $registro->region=Auth::user()->region;
        $registro->tipo=$request->tipo;
        $registro->dia_trabajo=$request->dia_trabajo;
        $registro->minutos=$request->minutos;
        $registro->save();
        
        return(view('mensaje',[ 'estatus'=>'OK',
                                'mensaje'=>'El registro de la actividad ('.$request->dia_trabajo.' - '.$request->tipo.') se realizo de manera exitosa!'
                              ]));
    }
    public function actividad_borrar(Request $request)
    {
        ActividadesExtra::find($request->id)->delete();
        return;
    }
}
