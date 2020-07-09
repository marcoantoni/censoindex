<?php

namespace App\Tree\School;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use App\Escola;
use Illuminate\Pipeline\Pipeline;

class School extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {

        $find = ['escola','colegio','instituto'];
        $found = $this->array_search($find, $tree->getTokens() );
        
        if ($found >= 0) {
            $tree->query = Escola::query();
            return app(Pipeline::class)
                ->send($tree)
                ->through([
                    Type::class,
                    Order::class
                ])->thenReturn();
        } else {
            return $next($tree);
        }
   }

    public function array_search(array $needle , array $haystack) {
        
        foreach ($needle as $key => $value) {
            $found = array_search ($value, $haystack);
            if ($found >= 0)
                return $found;
        }
        return false;
    }
}
