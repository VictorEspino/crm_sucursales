<?php

namespace App\Imports;

use App\Models\RentabilidadGastos;
use App\Models\Sucursal;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;

class RentabilidadGastosImport implements ToModel,WithHeadingRow,WithValidation,WithBatchInserts
{
    use Importable;
    private $carga_id;
    private $periodo;

    public function setCargaId($id)
    {
        $this->carga_id=$id;
    }
    public function setPeriodo($periodo)
    {
        $this->periodo=$periodo;
    }

    public function model(array $row)
    {
        $region=Sucursal::where('udn',$row['udn'])->get()->first()->region;
        return new RentabilidadGastos([
            'periodo'=>$this->periodo,
            'udn'=>$row['udn'],
            'region'=>$region,
            'gastos_fijos'=>$row['gastos_fijos'],
            'gastos_indirectos'=>$row['gastos_indirectos'],
            'carga_id'=>$this->carga_id,
            'empleado_carga'=>Auth::user()->empleado,
        ]);
    }
    public function rules(): array
    {
        return [
            '*.udn' => ['required','exists:sucursals,udn'],
            '*.gastos_fijos' => ['numeric'],
            '*.gastos_indirectos' => ['numeric'],
        ];
    }
    public function batchSize(): int
    {
        return 100;
    }
}
