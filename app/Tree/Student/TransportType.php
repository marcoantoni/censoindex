<?php

namespace App\Tree\Student;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Session;

class TransportType extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$match = false;
		$field = '';
		$value = 1;
		$schoolsFound = session('schoolsFound');
		$messageTransport = false;
		
		if (preg_match('/public/', $tree->sentence)) {
	        $match = true;
	        $field = 'IN_TRANSPORTE_PUBLICO';
	        $messageTransport = 'que usam transporte público';
		}

		if (preg_match('/bicicleta/', $tree->sentence)){
	        $match = true;
	        $field = 'IN_TRANSP_BICICLETA';
	        $messageTransport = 'que usam bicicleta';
		}

		if (preg_match('/(o|ô)nibus/', $tree->sentence)){
	        $match = true;
	        $field = 'IN_TRANSP_ONIBUS';
	        $messageTransport = 'que usam ônibus';
		}

		if (preg_match('/animal/', $tree->sentence)){
	        $match = true;
	        $field = 'IN_TRANSP_TR_ANIMAL';
	        $messageTransport = 'que usam transporte de tração animal';
		}

		if (preg_match('/vam/', $tree->sentence) || preg_match('/kombi/', $tree->sentence)){
	        $match = true;
	        $field = 'IN_TRANSP_VANS_KOMBI';
	        $messageTransport = 'que usam vam/kombi';
		}

		if ($match){
			if ($schoolsFound == 0 || $schoolsFound == 1){
				$tree->setQuery($tree->getQuery()->where($field, $value));
			} else {
				// Percorre o array $data da classe Answer
				// O indíce [0] armazena o nome da escola enquanto o [1] armazena o objeto da classe Builder que representa o número de alunos
				// Basta adicionar a restrição a consulta 
				foreach ($tree->answer->data as $key => $query) {
					$query[1]->where($field, '=', $value);
				}
			}

			// Adiciona a condição do transporte ao objeto builder para para gerar as estatísticas do meio de transporte utilizado
			if (session('schoolsFound') > 0){
				$tree->answer->statistics->query['city']['cache']->where($field, $value);
				$tree->answer->statistics->query['city']['nocache']->where($field, $value);
			}

			if (session('CO_MUNICIPIO')) {
				$tree->answer->statistics->query['state']['cache']->where($field, $value);
				$tree->answer->statistics->query['state']['nocache']->where($field, $value);
			}

			$tree->answer->statistics->fields[$field] = $value;
		}

		session(['messageTransport' => $messageTransport ]);

		$tree->answer->statistics->execute();

		return $next($tree);
	
	}
}
