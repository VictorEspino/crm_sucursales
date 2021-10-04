<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActividadesExtrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actividades_extras', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado'); //
            $table->string('nombre'); //
            $table->integer('udn'); //
            $table->string('pdv'); //
            $table->string('region'); //
            $table->date('dia_trabajo'); //
            $table->string('tipo');
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
        Schema::dropIfExists('actividades_extras');
    }
}
