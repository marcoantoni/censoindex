<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MatriculasCache extends Migration
{
    /**
     * Tabela para armazenar consultas já realizadas
     * Os campos se se referem a atributos encontrados nas perguntas
     * Armazena os resultados, evitando que consultas sejam geradas
     * @return void
     */
    public function up() {
        Schema::create('matriculas_cache', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('NU_ANO_CENSO')->default(2019);;
            $table->integer('CO_ENTIDADE')->default(0);
            $table->integer('CO_UF')->default(0);
            $table->integer('CO_MUNICIPIO')->default(0);
            $table->integer('IN_TRANSP_BICICLETA')->default(0);
            $table->integer('IN_TRANSP_MICRO_ONIBUS')->default(0);
            $table->integer('IN_TRANSP_ONIBUS')->default(0);
            $table->integer('IN_TRANSP_TR_ANIMAL')->default(0);
            $table->integer('IN_TRANSP_VANS_KOMBI')->default(0);
            $table->integer('IN_TRANSP_OUTRO_VEICULO')->default(0);
            $table->integer('IN_TRANSPORTE_PUBLICO')->default(0);
            $table->integer('QUANTIDADE');  // agregado dos atributos pesquisados
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
