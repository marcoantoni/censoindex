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
		$message = false;

		foreach ($tokens as $token => $value) {
			if (preg_match('/municip/i', $value)) {
				$value = 3;
				$message = "municipais";
				break;
			} elseif (preg_match('/estad/i', $value)) {
				$value = 2;
				$message = "estaduais";
				break;
			} elseif (preg_match('/federal/i', $value)) {
				$value = 1;
				break;
			}
			elseif (preg_match('/privad|particular/i', $value)) {
				$message = "particulares";
				$value = 4;
				break;
			} elseif (preg_match('/public/i', $value)) {
				$publicSchool = true;
				$message = "públicas";
				break;
			}
		}

		// aplica a restrição só de escolas funcionando normalmente - 1 em funcionamento
		$tree->setQuery($tree->getQuery()->where('TP_SITUACAO_FUNCIONAMENTO', 1));

		if ($publicSchool){
			$tree->setQuery(
				$tree->getQuery()->whereIn('TP_DEPENDENCIA', [1, 2, 3])
			);
		} else if ($value > 0){
			$tree->setQuery($tree->getQuery()->where('TP_DEPENDENCIA', $value));
		} 
		
		/* Personalização da mensagem apresenta para respostas relacionadas à escolas.
		 * A mensagem inicia com a palavra "Escolas" porém caso seja uma creche, será necessário
		 * substituir a palavra.
		*/
		if ($message)	
			session(['messageSchool' => "Escolas $message" ]);
		else
			session(['messageSchool' => "Escolas" ]);
		
		return $next($tree);

	}
}
