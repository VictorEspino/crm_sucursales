<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFunnelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('funnels', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado');
            $table->string('nombre');
            $table->integer('udn');
            $table->string('pdv');
            $table->string('region');
            $table->string('origen');
            $table->string('cliente');
            $table->string('telefono');
            $table->string('correo');
            $table->string('producto');
            $table->string('plan');
            $table->string('equipo');
            $table->string('estatus');
            $table->date('estatus1');
            $table->date('estatus2');
            $table->date('estatus3');
            $table->string('observaciones');
            $table->date('fecha_sig_contacto');
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
        Schema::dropIfExists('funnels');
    }
}
