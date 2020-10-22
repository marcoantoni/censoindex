<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Cursos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('cursos', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('CO_CURSO_EDUC_PROFISSIONAL');
            $table->string('NOME', 100);
            $table->string('ALIASNOME', 100);
            $table->string('EIXO', 100);
            $table->primary('id');
        });
    }

}
