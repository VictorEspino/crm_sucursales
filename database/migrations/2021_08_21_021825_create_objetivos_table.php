<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObjetivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('objetivos', function (Blueprint $table) {
            $table->id();
            $table->integer('udn');
            $table->string('pdv');
            $table->string('region');
            $table->string('periodo');
            $table->integer('ac');
            $table->integer('asi');
            $table->integer('rc');
            $table->integer('rs');
            $table->integer('ac_q1');
            $table->integer('as_q1');
            $table->integer('rc_q1');
            $table->integer('rs_q1');
            $table->integer('ac_q2');
            $table->integer('as_q2');
            $table->integer('rc_q2');
            $table->integer('rs_q2');
            $table->integer('ejecutivos');
            $table->integer('min_diario');
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
        Schema::dropIfExists('objetivos');
    }
}
