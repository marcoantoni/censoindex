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
		$value;

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
						$value = 14;
						$messageYear = ' no 1º ano';
						break;
					case 2:
						$value = 15;
						$messageYear = ' no 2º ano';
						break;
					case 3:
						$value = 16;
						$messageYear = ' no 3º ano';
						break;
					case 4:
						$value = 17;
						$messageYear = ' no 4º ano';
						break;
					case 5:
						$value = 18;
						$messageYear = ' no 5º ano';
						break;
					case 6:
						$value = 19;
						$messageYear = ' no 6º ano';
						break;
					case 7:
						$value = 20;
						$messageYear = ' no 7º ano';
						break;
					case 8:
						$value = 21;
						$messageYear = ' no 8º ano';
						break;
					case 9:
						$value = 41;
						$messageYear = ' no 9º ano';
						break;
        		}
        		$tree->setQuery($tree->getQuery()->where('TP_ETAPA_ENSINO', $value));
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
        
        session(['messageYear' => $messageYear ]);

		return $next($tree);
	}
}
