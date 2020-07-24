<?php

namespace App\Tree\Student;

use App\Curso;
use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Log;

class Course extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$tokens = $tree->getTokens();
		$position = array_search('tecnico', $tokens);

		if ($position) {
			for ($i = $position+1; $i < count($tokens); $i++) {
				
				$course = Curso::where('NOME', 'like', '%'.$tokens[$i].'%')->first();

				print(' ' . $course['CO_CURSO_EDUC_PROFISSIONAL'] . '<br>');
				if ($course) {
					$condition = array(
						'field' => 'CO_CURSO_EDUC_PROFISSIONAL',
						'operator' => '=',
						'value' => $course['CO_CURSO_EDUC_PROFISSIONAL']
		        	);
		        	$tree->addCondition($condition);
		        	break;
				}
			}
		}

		return $next($tree);

	}
	
	/*public function array_search(array $needle , array $haystack) {
        
        foreach ($needle as $key => $value) {
            $found = array_search ($value, $haystack);
            if ($found >= 0)
                return $found;
        }
        return false;
   	}*/
}
