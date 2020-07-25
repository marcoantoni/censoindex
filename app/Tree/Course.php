<?php

namespace App\Tree;

use App\Curso;
use App\Matricula;
use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use DB;

class Course extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {

		foreach ($tree->getConditions() as $key => $condition) {

			if ($condition['field'] == 'CO_MUNICIPIO') {
				$cityId =  $condition['value'];
				break;
			}
		}

		$coursesId = Matricula::distinct('CO_CURSO_EDUC_PROFISSIONAL')
			->where('CO_MUNICIPIO', '=', $cityId)
			->get(['CO_CURSO_EDUC_PROFISSIONAL'])
        	->toArray();

		$tree->query = DB::table('cursos')->whereIn('CO_CURSO_EDUC_PROFISSIONAL', array_values($coursesId));
		$tree->setOrder(['column' => 'NOME', 'order' => 'ASC']);

		return $next($tree);
	}	
}
