<?php


namespace App\Tree\School;

use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class Order extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
       
        if (array_intersect($tree->getTokens(), ['desc','decrescente','inverso'])) {
            $tree->setOrder(['column' => 'NO_ENTIDADE', 'order' => 'DESC']);        
        } else {
	        $tree->setOrder(['column' => 'NO_ENTIDADE', 'order' => 'ASC']);        
        } 

        return $next($tree);
    
    }

}
