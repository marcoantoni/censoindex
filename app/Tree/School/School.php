<?php


namespace App\Tree\School;

use App\Decorators\Token;
use App\Escola;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\DependencyEdge\Label;
use Illuminate\Pipeline\Pipeline;


class School extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {

        $find = ['escola','colegio','instituto'];
        $found = $this->array_search($find, $tree->getTokens() );
        
        if ($found >= 0) {
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
