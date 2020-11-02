<?php

namespace App\Tree\School;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;

class TypeSchool extends Branch {
    /*  
     * O campo TP_DEPENDENCIA indica a esfera administrativa da escola.
     * 1 - Federal
     * 2 - Estadual
     * 3 - Municipal
     * 4 - Privada
    */
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$tokens = $tree->getTokens();
		$value = 0;
		$publicSchool = false;
		
		foreach ($tokens as $token => $value) {
			if (preg_match('/municip/i', $value)) {
				$value = 3;
				break;
			} elseif (preg_match('/estad/i', $value)) {
				$value = 2;
				break;
			} elseif (preg_match('/federal/i', $value)) {
				$value = 1;
				break;
			}
			elseif (preg_match('/privad|particular/i', $value)) {
				$value = 4;
				break;
			} elseif (preg_match('/public/i', $value)) {
				$publicSchool = true;
				break;
			}
		}

		// aplica a restrição só de escolas funcionando normalmente - 1 em funcionamento
		$tree->setQuery($tree->getQuery()->where('TP_SITUACAO_FUNCIONAMENTO', 1));

		if ($value > 0){
			$tree->setQuery($tree->getQuery()->where('TP_DEPENDENCIA', $value));
		} else if ($publicSchool){
			$tree->setQuery(
				$tree->getQuery()->whereIn('TP_DEPENDENCIA', [1, 2, 3])
			);
		}

		return $next($tree);

	}
}
