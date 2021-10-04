<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeUpdatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_updates', function (Blueprint $table) {
            $table->id();
            $table->integer('empleado'); //
            $table->string('nombre'); //
            $table->integer('udn'); //
            $table->string('pdv'); //
            $table->string('region'); //
            $table->integer('minutos_orden')->default(0);
            $table->foreignId('orden_id')->default(0);
            $table->integer('minutos_funnel')->default(0);
            $table->foreignId('funnel_id')->default(0);
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
        Schema::dropIfExists('time_updates');
    }
}
