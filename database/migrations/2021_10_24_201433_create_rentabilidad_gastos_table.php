<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRentabilidadGastosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rentabilidad_gastos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('periodo');
            $table->integer('udn');
            $table->string('region',20);
            $table->float('gastos_fijos')->default(0);
            $table->float('gastos_indirectos')->default(0);
            $table->string('carga_id',10);
            $table->integer('empleado_carga');
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
        Schema::dropIfExists('rentabilidad_gastos');
    }
}
