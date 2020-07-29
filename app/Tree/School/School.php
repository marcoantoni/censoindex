<?php

namespace App\Tree\School;

use App\Escola;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Pipeline\Pipeline;

class School extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {

        $tree->setQuery(Escola::query());
        
        return app(Pipeline::class)
            ->send($tree)
            ->through([
                TypeSchool::class,
                Order::class
            ])->thenReturn();
   }
}
