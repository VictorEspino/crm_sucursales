<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErpTransaccion extends Model
{
    use HasFactory;

    protected $fillable=[
            'no_venta',
            'empleado',
            'fecha',
            'region',
            'pdv',
            'udn',
            'tipo',
            'importe',
            'ingreso',
            'costo_venta',
            'bracket',
            'tipo_estandar',
            'descripcion',
            'cliente',
            'dn',
            'servicio',
            'producto',
            'carga_id',
            'empleado_carga',
            'direccion',
    ];
}
