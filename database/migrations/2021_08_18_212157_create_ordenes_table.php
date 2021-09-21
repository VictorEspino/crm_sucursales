<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordenes', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado'); //
            $table->string('nombre'); //
            $table->integer('udn'); //
            $table->string('pdv'); //
            $table->string('region'); //
            $table->integer('edad'); //
            $table->date('f_nacimiento'); //
            $table->string('genero'); //
            $table->string('origen'); //
            $table->string('cliente'); //
            $table->string('numero_orden'); //
            $table->string('telefono'); //
            $table->string('correo'); //
            $table->string('producto'); //
            $table->string('flujo'); //
            $table->string('plan'); //
            $table->string('renta');
            $table->string('equipo'); //
            $table->string('porcentaje_requerido'); //
            $table->string('monto_total'); //
            $table->string('estatus_final');//
            $table->string('generada_en');
            $table->string('riesgo'); //
            $table->string('observaciones');//
            $table->integer('minutos');
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
        Schema::dropIfExists('ordenes');
    }
}
