<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComentariosController extends Controller
{
    public function mostrar_comentarios(Request $request)
    {
        $comentarios=Comentario::all();
        return(view('comentarios',['comentarios'=>$comentarios]));
    }
    public function save_comentarios(Request $request)
    {
        $request->validate([
                            'comentario'=>'required|max:255'
        ]);
        $registro=new Comentario;
        $registro->udn=Auth::user()->udn;
        $registro->periodo=session('periodo');
        $registro->region=Auth::user()->region;
        $registro->comentario=$request->comentario;
        $registro->empleado=Auth::user()->empleado;
        $registro->save();
        return(back()->withStatus('Nota registrada con exito'));

    }
}
