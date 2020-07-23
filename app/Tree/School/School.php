<?php

namespace App\Tree\School;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use App\Escola;
use Illuminate\Pipeline\Pipeline;

class School extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {

        $tree->query = Escola::query();
        return app(Pipeline::class)
            ->send($tree)
            ->through([
                Type::class,
                Order::class
            ])->thenReturn();
   }
}
