<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGeneracionDemandasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('generacion_demandas', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado'); //
            $table->string('nombre'); //
            $table->integer('udn'); //
            $table->string('pdv'); //
            $table->string('region'); //
            $table->date('dia_trabajo'); //
            $table->integer('sms');
            $table->integer('llamadas');
            $table->integer('rs');
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
        Schema::dropIfExists('generacion_demandas');
    }
}
