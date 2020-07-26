<?php

namespace App\Tree\Student;

use App\Escola;
use App\Matricula;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Illuminate\Database\Eloquent\Builder;
use Session;

class School extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$school = null;
		$cityId = null;

		foreach ($tree->getConditions() as $key => $condition) {

			if ($condition['field'] == 'CO_MUNICIPIO') {
				$cityId =  $condition['value'];
				break;
			}
		}
		print('Cidade: '.session('NOME_MUNICIPIO').' UF: '.session('NO_UF').'<br>');
		
		foreach ($tree->getEntityies() as $entity) {

			$entityName = $entity->getName();
			// ignore a entidade cujo nome é o municipio
			if (strcasecmp(session('NOME_MUNICIPIO'), $entityName) != 0) {
				if (strcasecmp(session('NO_UF'), $entityName) != 0) {
					$school = Escola::where('CO_MUNICIPIO', $cityId)->where('NO_ENTIDADE', 'like', '%'.$entityName.'%')->first();
					if ($school){
						$condition = array(
							'field' => 'CO_ENTIDADE',
							'operator' => '=',
							'value' => $school['CO_ENTIDADE']
						);	
						$tree->addCondition($condition);
						$schoolsFound++;
					}
				}		
			} 
		}

		// Busca as estatistícas de matrículas da cidade e estado
		// Se encontrou uma escola, busca as informacoes do município
		if ($school){
			$tree->answer->statistics['city'] = Matricula::where('CO_MUNICIPIO', $cityId)->count();
		} 

		// Se não encontrou um município, a pesquisa esta sendo feita pelo estado
		// Não e necessário as estatistícas do estado
		if (session('CO_MUNICIPIO')) {
			$tree->answer->statistics['state'] = Matricula::where('CO_UF', session('CO_UF') )->count();
		}

		return $next($tree);
	}
}
