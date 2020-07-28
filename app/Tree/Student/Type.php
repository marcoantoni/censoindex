<?php

namespace App\Tree\Student;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Session;

class Type extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$condition = false;
		$schoolsFound = session('schoolsFound');
		$messageTransport = false;
		
		if (preg_match('/public/', $tree->sentence)) {
	        $condition = array(
	           'field' => 'IN_TRANSPORTE_PUBLICO',
	            'operator' => '=',
	            'value' => 1
	        );
	        $messageTransport = 'que usam transporte público';
		}

		if (preg_match(('/bicicleta/'), $tree->sentence)){
	        $condition = array(
	           'field' => 'IN_TRANSP_BICICLETA',
	            'operator' => '=',
	            'value' => 1
	        );
	        $messageTransport = 'que usam bicicleta';
		}

		if (preg_match(('/onibus/'), $tree->sentence)){
	        $condition = array(
	           'field' => 'IN_TRANSP_ONIBUS',
	            'operator' => '=',
	            'value' => 1
	        );
	        $messageTransport = 'que usam ônibus';
		}

		if (preg_match(('/animal/'), $tree->sentence)){
	        $condition = array(
	           'field' => 'IN_TRANSP_TR_ANIMAL',
	            'operator' => '=',
	            'value' => 1
	        );
	        $messageTransport = 'que usam transporte de tração animal';
		}

		if ($condition){
			if ($schoolsFound == 1){
				$tree->addCondition($condition);
			} else if ($schoolsFound > 1){
				// Percorre o array $data da classe Answer
				// O indíce [0] armazena o nome da escola enquanto o [1] armazena o objeto da classe Builder que representa o número de alunos
				// Basta adicionar a restrição a consulta 
				foreach ($tree->answer->data as $key => $value) {
					//print_r($value);
					$value[1]->where($condition['field'], $condition['operator'], $condition['value']);
				}
			}
			session(['messageTransport' => $messageTransport ]);
		}

		return $next($tree);
	
	}
}
