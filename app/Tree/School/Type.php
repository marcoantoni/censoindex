<?php

namespace App\Tree\School;

use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Type extends Branch {
	
	const PUBLICSCHOOL = 0; 
	const PRIVATECSCHOOL = 1; 
	const COMUNITARYSCHOOL = 2; 
	const CONFESSIONALSCHOOL = 3; 
	const PHILANTHROPICSCHOOL = 4; 

	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$condition = '';
		$hasToken = array_intersect($tree->getTokens(), ['publico', 'publicas', 'privado', 'particular']);

		if ($hasToken) {

			$tokens = $tree->getTokens();
			$value = '';
			$typeSchol = '';
			$operator = '=';

			foreach ($tokens as $token => $value) {
				if ($value == 'publico' || $value == 'publicas') {
					$typeSchol = 0;
					break;
				} elseif ($value == 'privado' || $value == 'particular') {
					$typeSchol = '0';
					$operator = '<>';
					break;
				}
			}

	        $condition = array(
	           'field' => 'TP_CATEGORIA_ESCOLA_PRIVADA',
	            'operator' => $operator,
	            'value' => $typeSchol
	        );

	        $tree->addCondition($condition);
	    }

		return $next($tree);
	
	}
}
