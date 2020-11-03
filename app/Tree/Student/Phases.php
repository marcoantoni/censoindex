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
		$values = false;
		$schoolsFound = session('schoolsFound');

		$tokens = $tree->getTokens();

		foreach ($tokens as $key => $token) { 
			
			if (preg_match('/creche/i', $token)) {		
				$values = [1];
				$messagePhase = "nas creches";
				unset($tokens[$key]);
				break;
			}

			if (preg_match('/pr(e|é)( |-)escola/i', $tree->sentence)) {		
				$values = [2];
				$messagePhase = "nas pré-escolas";
				
				$position = array_search('pre', $tokens);
				if ($position) unset($tokens[$position]);
				
				$position = array_search('escola', $tokens);
				if ($position) unset($tokens[$position]);	
				break;		
			}

			if (preg_match('/infantil/i', $token)) {		
				$values = [1, 2];
				$messagePhase = "na educação infantil";
				unset($tokens[$key]);
				break;
			}
			
			// Fundamental e Médio vem precedido de "ensino"
			// Procura os dois termos depois que encontrar "ensino"
			$position = array_search('ensino', $tokens);

			if ($position) {
				for ($i = $position+1; $i < count($tokens); $i++) {
				 	if (preg_match('/fundamental/i', $tokens[$i])) {		
						if (preg_match('/eja/i', $tree->sentence)) {
							$values = [69, 70, 72];
							$messagePhase = "no EJA - ensino Fundamental";
							break;
						}  else {
							// códigos entre 4 e 21 correspondem ao ensino fundamental
							// código 41 corresponde ao 9º ano do novo ensino fundamental
							$values = [4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 41];
							$messagePhase = "no ensino Fundamental";
						}
						// para o laço depois de encontrar a palavra fundamental
						break;
					} else if (preg_match('/medio/i', $tokens[$i])) {
						if (preg_match('/eja/i', $tree->sentence)) {
							// pesquisa por EJA médio
							$values = [71, 74];
							$messagePhase = "no EJA - ensino Médio";
							break;
						} else {
							// códigos entre 25 e 28 correspondem ao ensino médio - regular e integrado
							$values = [25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 64, 71, 74];
							$messagePhase = "no ensino Médio";
						}
						// para o laço depois de encontrar a palavra fundamental
						break;
					} 
				}
			}
		}

		if ($values){
			if ($schoolsFound == 0 || $schoolsFound == 1){
				$tree->setQuery($tree->getQuery()->whereIn('TP_ETAPA_ENSINO', array_values($values)));
			} else {
				// Percorre o array $data da classe Answer
				// O indíce [0] armazena o nome da escola enquanto o [1] armazena o objeto da classe Builder que representa o número de alunos
				// Basta adicionar a restrição a consulta 
				foreach ($tree->answer->data as $key => $query) {
					$query[1]->whereIn('TP_ETAPA_ENSINO', array_values($values));
				}
			}
			session(['messagePhase' => $messagePhase ]);
		}

		return $next($tree);
	}
}
