<?php

namespace App\Tree\Course;

use App\Curso;
use App\Matricula;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use App\Tree\Student\School;
use Closure;
use Illuminate\Pipeline\Pipeline;
use DB;
use Session;

class Course extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {

		foreach ($tree->getConditions() as $key => $condition) {

			if ($condition['field'] == 'CO_MUNICIPIO') {
				$cityId =  $condition['value'];
				break;
			}
		}

		// Vai para o App\Tree\Student\School para verificar se a pergunta possui uma escola
        app(Pipeline::class)
            ->send($tree)
            ->through([
                School::class,
            ])->thenReturn();    

		if (session('CO_ENTIDADE')){
			$coursesId = Matricula::distinct('CO_CURSO_EDUC_PROFISSIONAL')
				->where('CO_ENTIDADE', '=', session('CO_ENTIDADE') )
				->get(['CO_CURSO_EDUC_PROFISSIONAL'])
				->toArray();
		}else{
			$coursesId = Matricula::distinct('CO_CURSO_EDUC_PROFISSIONAL')
				->where('CO_MUNICIPIO', '=', $cityId)
				->get(['CO_CURSO_EDUC_PROFISSIONAL'])
				->toArray();
		}
		
		$tree->query = DB::table('cursos')->whereIn('CO_CURSO_EDUC_PROFISSIONAL', array_values($coursesId));
		$tree->setOrder(['column' => 'NOME', 'order' => 'ASC']);

		return $next($tree);
	}	
}
