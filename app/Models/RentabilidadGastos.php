<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentabilidadGastos extends Model
{
    use HasFactory;
    protected $fillable=['udn',
                        'region',
                        'periodo',
                        'gastos_fijos',
                        'gastos_indirectos',
                        'carga_id',
                        'empleado_carga'];
}
