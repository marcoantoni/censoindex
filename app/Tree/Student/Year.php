<?php

namespace App\Tree\Student;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Session;

class Year extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
	
		/* Os valores utilizados na coluna TP_ETAPA_ENSINO estão presentes na tabela matrículas
		 * A descrição completa está disponível no dicionário de dados
		*/

		$messagePhase = session('messagePhase');
		$schoolsFound = session('schoolsFound');
		$messageYear = '';
		$year = 0;
		$values = false;

        foreach ($tree->getEntityies() as $entity) {
           if ($entity->getType() == EntityType::NUMBER ) {
                $year = (int)$entity->getName();
                break;
            }
        }

        // Caso encontrou um numeral
        if ($year > 0){
        	if (strcmp($messagePhase, "no ensino Fundamental") == 0){
        		switch($year){
					case 1:
						$values = 14;
						$messageYear = ' no 1º ano';
						break;
					case 2:
						$values = 15;
						$messageYear = ' no 2º ano';
						break;
					case 3:
						$values = 16;
						$messageYear = ' no 3º ano';
						break;
					case 4:
						$values = 17;
						$messageYear = ' no 4º ano';
						break;
					case 5:
						$values = 18;
						$messageYear = ' no 5º ano';
						break;
					case 6:
						$values = 19;
						$messageYear = ' no 6º ano';
						break;
					case 7:
						$values = 20;
						$messageYear = ' no 7º ano';
						break;
					case 8:
						$values = 21;
						$messageYear = ' no 8º ano';
						break;
					case 9:
						$values = 41;
						$messageYear = ' no 9º ano';
						break;
        		}
        		$tree->setQuery($tree->getQuery()->where('TP_ETAPA_ENSINO', $values));
        	} else if (strcmp($messagePhase, "no ensino Médio") == 0){

        		$values = [];
        		switch($year){
					case 1:
						$values = [25, 30, 35];
						$messageYear = ' no 1º ano';
						break;
					case 2:
						$values = [26, 31, 36];
						$messageYear = ' no 2º ano';
						break;
					case 3:
						$values = [27, 32, 37];
						$messageYear = ' no 3º ano';
						break;
					case 4:
						$values = [27, 33, 38];
						$messageYear = ' no 4º ano';
						break;
        		}
        		$tree->setQuery($tree->getQuery()->whereIn('TP_ETAPA_ENSINO', $values)); 
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
        	session(['messageYear' => $messageYear ]);
		}
        
		return $next($tree);
	}
}
