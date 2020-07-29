<?php

namespace App;

use Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Curso extends Model {

	protected $table = 'cursos'; 

	public static function getCoursesByCity(int $cityId){
		$sql = "SELECT * FROM cursos WHERE CO_CURSO_EDUC_PROFISSIONAL in (SELECT DISTINCT CO_CURSO_EDUC_PROFISSIONAL FROM matriculas WHERE CO_MUNICIPIO = $cityId ORDER BY NOME ASC)";
		return DB::select($sql);
	}
}
