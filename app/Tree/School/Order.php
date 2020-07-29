<?php

namespace App\Tree\School;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;

class Order extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
       
        if (array_intersect($tree->getTokens(), ['desc','decrescente','inverso'])) {
            $tree->setQuery($tree->getQuery()->orderBy('NO_ENTIDADE', 'DESC'));  
        } else {
	        $tree->setQuery($tree->getQuery()->orderBy('NO_ENTIDADE', 'ASC'));
        } 

        return $next($tree);
    
    }

}
