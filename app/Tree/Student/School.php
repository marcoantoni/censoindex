<?php

namespace App\Tree\Student;

use App\Escola;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Illuminate\Database\Eloquent\Builder;
use Log;

class School extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		print("<br>Student/School");
		
		$school = null;
		$cityId = null;

		foreach ($tree->getConditions() as $key => $condition) {

			if ($condition['field'] == 'CO_MUNICIPIO') {
				$cityId =  $condition['value'];
				print('<br>CO_MUNICIPIO = '. $cityId);
				break;
			}
		}

		foreach ($tree->getEntityies() as $entity) {
           if ($entity->getType() == EntityType::PERSON || $entity->getType() == EntityType::OTHER) {

                $entityName = $entity->getName();
        		        		
        		$school = Escola::where('CO_MUNICIPIO', $cityId)->where('NO_ENTIDADE', 'like', '%'.$entityName.'%')->first(); 

        		if ($school){
        			print($school['NO_ENTIDADE'] . ' id: '.$school['CO_ENTIDADE']);
					$condition = array(
						'field' => 'CO_ENTIDADE',
						'operator' => '=',
						'value' => $school['CO_ENTIDADE']
					);
					$tree->addCondition($condition);
        			break;
        		}

            } 
        }
		return $next($tree);
	}
}
