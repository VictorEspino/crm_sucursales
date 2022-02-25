<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateErpTransaccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('erp_transaccions', function (Blueprint $table) {
            $table->id();
            $table->integer('no_venta');
            $table->string('empleado');
            $table->date('fecha');
            $table->string('region');
            $table->string('pdv');
            $table->foreignId('udn');
            $table->string('tipo');
            $table->float('importe');
            $table->float('ingreso');
            $table->float('costo_venta');
            $table->integer('bracket',3);
            $table->string('tipo_estandar',3);
            $table->string('descripcion')->nullable();
            $table->string('cliente')->nullable();
            $table->string('dn')->nullable();
            $table->string('servicio')->nullable();
            $table->string('producto')->nullable();
            $table->string('carga_id',10);
            $table->integer('empleado_carga');
            $table->string('direccion')->default('SUCURSALES');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('erp_transaccions');
    }
}
