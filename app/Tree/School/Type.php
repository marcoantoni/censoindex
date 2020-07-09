<?php

namespace App\Tree\School;

use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;

class Type extends Branch {
	
	const PUBLICSCHOOL = 0; 
	const PRIVATECSCHOOL = 1; 
	const COMUNITARYSCHOOL = 2; 
	const CONFESSIONALSCHOOL = 3; 
	const PHILANTHROPICSCHOOL = 4; 

	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$condition = '';
		$hasToken = array_intersect($tree->getTokens(), ['publico', 'publicas', 'privado', 'particular']);

		$tokens = $tree->getTokens();
		$value = '';
		$typeSchol = '';
		$operator = '=';

		if ($hasToken) {

			foreach ($tokens as $token => $value) {
				if (preg_match('/public/', $value)) {
					$typeSchol = self::PUBLICSCHOOL;
					break;
				} elseif (preg_match('/privad|particular/', $value))  {
					$typeSchol = self::PUBLICSCHOOL;
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

	    // search for public schools:
	    $hasToken = array_intersect($tree->getTokens(), ['municipal', 'municipio ', 'estadual', 'estado', 'federal']); 

		if ($hasToken) {
			$operator = '=';
			foreach ($tokens as $token => $value) {
				if (preg_match('/municip/', $value)) {
					$typeSchol = 3;
					break;
				} elseif (preg_match('/estad/', $value)) {
					$typeSchol = 2;
					break;
				} elseif (preg_match('/federal/', $value)) {
					$typeSchol = 1;
					break;
				}
			}

	        $condition = array(
	           	'field' => 'TP_DEPENDENCIA',
	            'operator' => $operator,
	            'value' => $typeSchol
	        );

	        $tree->addCondition($condition);
	    }

		return $next($tree);
	
	}
}
