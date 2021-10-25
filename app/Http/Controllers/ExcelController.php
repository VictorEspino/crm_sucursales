<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ErpTransaccionesImport;
use App\Imports\RentabilidadGastosImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Sucursal;
use App\Models\ErpTransaccion;
use Illuminate\Support\Str;

class ExcelController extends Controller
{
    public function erp_import(Request $request) 
    {
        $request->validate([
            'file'=> 'required',
            ]);
        $file=$request->file('file');

        $sucursales=Sucursal::select('udn','pdv')->get()->pluck('udn','pdv');

        $bytes = random_bytes(5);
        $carga_id=bin2hex($bytes);

        //return("&&".Str::slug('Número DN')."&&");

        $import=new ErpTransaccionesImport;
        $import->setCargaId($carga_id);
        $import->setSucursales($sucursales);
        try{
        $import->import($file);
        }
        catch(\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->withFailures($e->failures());
        }  

        $errores=$this->validar_carga($carga_id);
        if(!empty($errores))
        {
            $this->borrar_carga_erp($carga_id);
            return(back()->with('error_validacion',$errores));
        }
        return back()->withStatus('Archivo cargado con exito!');
    }
    public function validar_carga($carga_id)
    {
        $cargados=ErpTransaccion::select(DB::raw('distinct no_venta'))
                                ->where('carga_id',$carga_id)
                                ->get();
        ErpTransaccion::where('carga_id','!=',$carga_id)
                        ->whereIn('no_venta',$cargados->pluck('no_venta'))
                        ->delete();

    }
    public function borrar_carga_erp($carga_id)
    {
        
    }
    public function gastos_import(Request $request)
    {
        $request->validate([
            'file'=> 'required',
            ]);
        $file=$request->file('file');

        $bytes = random_bytes(5);
        $carga_id=bin2hex($bytes);

        $import=new RentabilidadGastosImport;
        $import->setCargaId($carga_id);
        $import->setPeriodo($request->periodo);
        try{
            $import->import($file);
            }
        catch(\Maatwebsite\Excel\Validators\ValidationException $e) {
            return back()->withFailures($e->failures());
        }  
        return back()->withStatus('Archivo cargado con exito!');
    }
}
