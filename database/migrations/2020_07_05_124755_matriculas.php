<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Matriculas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('matriculas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('NU_ANO_CENSO');
            $table->char('ID_ALUNO', 32);
            $table->integer('ID_MATRICULA')->default(0);
            $table->integer('NU_MES')->default(0);
            $table->integer('NU_ANO')->default(0);
            $table->integer('NU_IDADE_REFERENCIA')->default(0);
            $table->integer('NU_IDADE')->default(0);
            $table->integer('TP_SEXO')->default(0);
            $table->integer('TP_COR_RACA')->default(0);
            $table->integer('TP_NACIONALIDADE')->default(0);
            $table->integer('CO_PAIS_ORIGEM')->default(0);
            $table->integer('CO_UF_NASC')->default(0);
            $table->integer('CO_MUNICIPIO_NASC')->default(0);
            $table->integer('CO_UF_END')->default(0);
            $table->integer('CO_MUNICIPIO_END')->default(0);
            $table->integer('TP_ZONA_RESIDENCIAL')->default(0);
            $table->integer('TP_LOCAL_RESID_DIFERENCIADA')->default(0);
            $table->integer('IN_NECESSIDADE_ESPECIAL')->default(0);
            $table->integer('IN_BAIXA_VISAO')->default(0);
            $table->integer('IN_CEGUEIRA')->default(0);
            $table->integer('IN_DEF_AUDITIVA')->default(0);
            $table->integer('IN_DEF_FISICA')->default(0);
            $table->integer('IN_DEF_INTELECTUAL')->default(0);
            $table->integer('IN_SURDEZ')->default(0);
            $table->integer('IN_SURDOCEGUEIRA')->default(0);
            $table->integer('IN_DEF_MULTIPLA')->default(0);
            $table->integer('IN_AUTISMO')->default(0);
            $table->integer('IN_SUPERDOTACAO')->default(0);
            $table->integer('IN_RECURSO_LEDOR')->default(0);
            $table->integer('IN_RECURSO_TRANSCRICAO')->default(0);
            $table->integer('IN_RECURSO_INTERPRETE')->default(0);
            $table->integer('IN_RECURSO_LIBRAS')->default(0);
            $table->integer('IN_RECURSO_LABIAL')->default(0);
            $table->integer('IN_RECURSO_AMPLIADA_18')->default(0);
            $table->integer('IN_RECURSO_AMPLIADA_24')->default(0);
            $table->integer('IN_RECURSO_CD_AUDIO')->default(0);
            $table->integer('IN_RECURSO_PROVA_PORTUGUES')->default(0);
            $table->integer('IN_RECURSO_VIDEO_LIBRAS')->default(0);
            $table->integer('IN_RECURSO_BRAILLE')->default(0);
            $table->integer('IN_RECURSO_NENHUM')->default(0);
            $table->integer('IN_AEE_LIBRAS')->default(0);
            $table->integer('IN_AEE_LINGUA_PORTUGUESA')->default(0);
            $table->integer('IN_AEE_INFORMATICA_ACESSIVEL')->default(0);
            $table->integer('IN_AEE_BRAILLE')->default(0);
            $table->integer('IN_AEE_CAA')->default(0);
            $table->integer('IN_AEE_SOROBAN')->default(0);
            $table->integer('IN_AEE_VIDA_AUTONOMA')->default(0);
            $table->integer('IN_AEE_OPTICOS_NAO_OPTICOS')->default(0);
            $table->integer('IN_AEE_ENRIQ_CURRICULAR')->default(0);
            $table->integer('IN_AEE_DESEN_COGNITIVO')->default(0);
            $table->integer('IN_AEE_MOBILIDADE')->default(0);
            $table->integer('TP_OUTRO_LOCAL_AULA')->default(0);
            $table->integer('IN_TRANSPORTE_PUBLICO')->default(0);
            $table->integer('TP_RESPONSAVEL_TRANSPORTE')->default(0);
            $table->integer('IN_TRANSP_BICICLETA')->default(0);
            $table->integer('IN_TRANSP_MICRO_ONIBUS')->default(0);
            $table->integer('IN_TRANSP_ONIBUS')->default(0);
            $table->integer('IN_TRANSP_TR_ANIMAL')->default(0);
            $table->integer('IN_TRANSP_VANS_KOMBI')->default(0);
            $table->integer('IN_TRANSP_OUTRO_VEICULO')->default(0);
            $table->integer('IN_TRANSP_EMBAR_ATE5')->default(0);
            $table->integer('IN_TRANSP_EMBAR_5A15')->default(0);
            $table->integer('IN_TRANSP_EMBAR_15A35')->default(0);
            $table->integer('IN_TRANSP_EMBAR_35')->default(0);
            $table->integer('TP_ETAPA_ENSINO')->default(0);
            $table->integer('IN_ESPECIAL_EXCLUSIVA')->default(0);
            $table->integer('IN_REGULAR')->default(0);
            $table->integer('IN_EJA')->default(0);
            $table->integer('IN_PROFISSIONALIZANTE')->default(0);
            $table->integer('ID_TURMA')->default(0);
            $table->integer('CO_CURSO_EDUC_PROFISSIONAL')->default(0);
            $table->integer('TP_MEDIACAO_DIDATICO_PEDAGO')->default(0);
            $table->integer('NU_DURACAO_TURMA')->default(0);
            $table->integer('NU_DUR_ATIV_COMP_MESMA_REDE')->default(0);
            $table->integer('NU_DUR_ATIV_COMP_OUTRAS_REDES')->default(0);
            $table->integer('NU_DUR_AEE_MESMA_REDE')->default(0);
            $table->integer('NU_DUR_AEE_OUTRAS_REDES')->default(0);
            $table->integer('NU_DIAS_ATIVIDADE')->default(0);
            $table->integer('TP_UNIFICADA')->default(0);
            $table->integer('TP_TIPO_ATENDIMENTO_TURMA')->default(0);
            $table->integer('TP_TIPO_LOCAL_TURMA')->default(0);
            $table->integer('CO_ENTIDADE')->default(0);
            $table->integer('CO_REGIAO')->default(0);
            $table->integer('CO_MESORREGIAO')->default(0);
            $table->integer('CO_MICRORREGIAO')->default(0);
            $table->integer('CO_UF')->default(0);
            $table->integer('CO_MUNICIPIO')->default(0);
            $table->integer('CO_DISTRITO')->default(0);
            $table->integer('TP_DEPENDENCIA')->default(0);
            $table->integer('TP_LOCALIZACAO')->default(0);
            $table->integer('TP_CATEGORIA_ESCOLA_PRIVADA')->default(0);
            $table->integer('IN_CONVENIADA_PP')->default(0);
            $table->integer('TP_CONVENIO_PODER_PUBLICO')->default(0);
            $table->integer('IN_MANT_ESCOLA_PRIVADA_EMP')->default(0);
            $table->integer('IN_MANT_ESCOLA_PRIVADA_ONG')->default(0);
            $table->integer('IN_MANT_ESCOLA_PRIVADA_OSCIP')->default(0);
            $table->integer('IN_MANT_ESCOLA_PRIV_ONG_OSCIP')->default(0);
            $table->integer('IN_MANT_ESCOLA_PRIVADA_SIND')->default(0);
            $table->integer('IN_MANT_ESCOLA_PRIVADA_SIST_S')->default(0);
            $table->integer('IN_MANT_ESCOLA_PRIVADA_S_FINS')->default(0);
            $table->integer('TP_REGULAMENTACAO')->default(0);
            $table->integer('TP_LOCALIZACAO_DIFERENCIADA')->default(0);
            $table->integer('IN_EDUCACAO_INDIGENA')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        //
    }
}
