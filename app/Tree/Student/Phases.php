<?php

namespace App\Tree\Student;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Session;

class Phases extends Branch {
	
	/* Os valores utilizados na coluna TP_ETAPA_ENSINO estão presentes na tabela matrículas
	 * A descrição completa está disponível no dicionário de dados
	*/

	function handle(DecisionTree $tree, Closure $next): DecisionTree {

		$sentence = $tree->sentence;
		$messagePhase = false;

		if (preg_match('/cre(ch|x)e/', $tree->sentence)) {		
			$tree->setQuery($tree->getQuery()->where('TP_ETAPA_ENSINO', 1));
			$messagePhase = "nas creches";
		}

		if (preg_match('/pre-escola/', $tree->sentence)) {		
			$tree->setQuery($tree->getQuery()->where('TP_ETAPA_ENSINO', 2));
			$messagePhase = "nas pré-escolas";
		}

		// pesquisa por EJA fundamental
		if (preg_match('/eja/', $tree->sentence) && preg_match('/fundamental/', $tree->sentence)) {		
			$tree->setQuery($tree->getQuery()->whereBetween('TP_ETAPA_ENSINO', [69,70]));
			$messagePhase = "no EJA - Ensino Fundamental";
		}		
		
		// pesquisa por EJA médio
		if (preg_match('/eja/', $tree->sentence) && preg_match('/m(e|é)dio/', $tree->sentence)) {		
			$tree->setQuery($tree->getQuery()->where('TP_ETAPA_ENSINO', [71]));
			$messagePhase = "no EJA - Ensino Médio";
		}
		
		// Fundamental e Médio vem precedido de "ensino"
		// Procura os dois termos depois que encontrar "ensino"
		
		$tokens = $tree->getTokens();
		$position = array_search('ensino', $tokens);

		if ($position) {
			for ($i = $position+1; $i < count($tokens); $i++) {
				
			 	if (preg_match('/fundamental/', $tokens[$i])) {		
					// códigos entre 4 e 21 correspondem ao ensino fundamental
					// código 41 corresponde ao 9º ano do novo ensino fundamental
					$tree->setQuery($tree->getQuery()
						->whereIn('TP_ETAPA_ENSINO', 
							[4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 41]
						)
					);
					$messagePhase = "no ensino Fundamental";
					break;
				} else if (preg_match('/m(e|é)dio/', $tokens[$i])) {		
					// códigos entre 25 e 28 correspondem ao ensino médio - regular e integrado
					$tree->setQuery($tree->getQuery()
						->whereIn('TP_ETAPA_ENSINO', [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 71, 74])
					);
					$messagePhase = "no ensino Médio";
					break;
				} 
			}
		}

		session(['messagePhase' => $messagePhase ]);

		return $next($tree);
	}
}
