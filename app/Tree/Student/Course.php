<?php

namespace App\Tree\Student;

use App\Curso;
use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;

class Course extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$tokens = $tree->getTokens();
		$position = array_search('tecnico', $tokens);

		if ($position) {
			for ($i = $position+1; $i < count($tokens); $i++) {
				
				$course = Curso::where('NOME', 'like', '%'.$tokens[$i].'%')->first();

				if ($course) {
					$condition = array(
						'field' => 'CO_CURSO_EDUC_PROFISSIONAL',
						'operator' => '=',
						'value' => $course['CO_CURSO_EDUC_PROFISSIONAL']
		        	);
		        	$tree->setQuery($tree->getQuery()->where('CO_CURSO_EDUC_PROFISSIONAL', $course['CO_CURSO_EDUC_PROFISSIONAL']));
		        	break;
				}
			}
		}

		return $next($tree);

	}
}
