<?php

namespace App\Tree\Student;

use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Log;

class Type extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		if (preg_match('/public/', $tree->sentence)) {
	        $condition = array(
	           'field' => 'IN_TRANSPORTE_PUBLICO',
	            'operator' => '=',
	            'value' => 1
	        );

	        $tree->addCondition($condition);
		}

		if (preg_match(('/bicicleta/'), $tree->sentence)){
	        printf("quantos usam bicicleta");
	        $condition = array(
	           'field' => 'IN_TRANSP_BICICLETA',
	            'operator' => '=',
	            'value' => 1
	        );

        	$tree->addCondition($condition);
		}

		if (preg_match(('/onibus/'), $tree->sentence)){
	        printf("quantos usam onibus");
	        $condition = array(
	           'field' => 'IN_TRANSP_ONIBUS',
	            'operator' => '=',
	            'value' => 1
	        );
	        
        	$tree->addCondition($condition);
		}

		if (preg_match(('/animal/'), $tree->sentence)){
	        printf("quantos usam traspor tração animal");
	        $condition = array(
	           'field' => 'IN_TRANSP_TR_ANIMAL',
	            'operator' => '=',
	            'value' => 1
	        );
        	$tree->addCondition($condition);
		}

		return $next($tree);
	
	}
}
