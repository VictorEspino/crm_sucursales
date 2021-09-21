<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteraccionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('interaccions', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado');
            $table->string('nombre');
            $table->integer('udn');
            $table->string('pdv');
            $table->string('region');
            $table->string('tramite');
            $table->boolean('intencion');
            $table->string('fin_interaccion');
            $table->string('telefono');
            $table->string('observaciones');
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
        Schema::dropIfExists('interaccions');
    }
}
