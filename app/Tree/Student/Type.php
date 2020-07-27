<?php

namespace App\Tree\Student;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Session;

class Type extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$condition = null;
		$schoolsFound = session('schoolsFound');
		
		print ('<br> schoolsFound = ' . session('schoolsFound' ));
		if (preg_match('/public/', $tree->sentence)) {
	        $condition = array(
	           'field' => 'IN_TRANSPORTE_PUBLICO',
	            'operator' => '=',
	            'value' => 1
	        );
		}

		if (preg_match(('/bicicleta/'), $tree->sentence)){
	        printf("quantos usam bicicleta");
	        $condition = array(
	           'field' => 'IN_TRANSP_BICICLETA',
	            'operator' => '=',
	            'value' => 1
	        );
		}

		if (preg_match(('/onibus/'), $tree->sentence)){
	        printf("quantos usam onibus");
	        $condition = array(
	           'field' => 'IN_TRANSP_ONIBUS',
	            'operator' => '=',
	            'value' => 1
	        );
		}

		if (preg_match(('/animal/'), $tree->sentence)){
	        printf("quantos usam traspor tração animal");
	        $condition = array(
	           'field' => 'IN_TRANSP_TR_ANIMAL',
	            'operator' => '=',
	            'value' => 1
	        );
		}

		if ($schoolsFound == 0 || $schoolsFound == 1){
			print_r($condition);
			//$tree->addCondition([$condition['field'], $condition['operator'], $condition['value']]);
		} else {
			// Percorre o array $data da classe Answer
			// // O indíce [0] armazena o nome da escola enquanto o [1] armazena o objeto da classe Builder que representa o número de alunos
			// Basta adicionar a restrição a consulta 
			foreach ($tree->answer->data as $key => $value) {
			//foreach ($tree->answer->data as $key => $value) {
				$value[1]->where($condition['field'], $condition['operator'], $condition['value']);
			}
		}

		return $next($tree);
	
	}
}
