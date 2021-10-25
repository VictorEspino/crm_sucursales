<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentabilidadPeriodosGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentabilidad_periodos_gastos', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->date('inicio_vigencia');
            $table->date('fin_vigencia');
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
        Schema::dropIfExists('rentabilidad_periodos_gastos');
    }
}
