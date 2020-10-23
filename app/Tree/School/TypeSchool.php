<?php

namespace App\Tree\School;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;

class TypeSchool extends Branch {
	
	const PUBLICSCHOOL = 0; 
	const PRIVATECSCHOOL = 1; 
	const COMUNITARYSCHOOL = 2; 
	const CONFESSIONALSCHOOL = 3; 
	const PHILANTHROPICSCHOOL = 4;

	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$hasToken = array_intersect($tree->getTokens(), ['publico', 'publicas', 'privado', 'particular']);

		$tokens = $tree->getTokens();
		$value = '';
		$typeSchol = '';
		$operator = '=';

		foreach ($tokens as $token => $value) {
			if (preg_match('/public/i', $value)) {
				$typeSchol = self::PUBLICSCHOOL;
				break;
			} elseif (preg_match('/privad|particular/i', $value))  {
				$typeSchol = self::PUBLICSCHOOL;
				$operator = '<>';
				break;
			}
		}

		if ($typeSchol == 1 || $typeSchol != 1) {
			$tree->setQuery($tree->getQuery()->where('TP_CATEGORIA_ESCOLA_PRIVADA', $operator, $typeSchol));
		}

		// search for public schools:
		foreach ($tokens as $token => $value) {
			if (preg_match('/municip/i', $value)) {
				$typeSchol = 3;
				break;
			} elseif (preg_match('/estad/i', $value)) {
				$typeSchol = 2;
				break;
			} elseif (preg_match('/federal/i', $value)) {
				$typeSchol = 1;
				break;
			}
		}

		if ($typeSchol >= 1 && $typeSchol <= 3){
			$tree->setQuery($tree->getQuery()->where('TP_DEPENDENCIA', $typeSchol));
		}

		return $next($tree);

	}
}
