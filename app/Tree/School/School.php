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

        if (array_intersect($tree->getTokens(), ['escola','colegio','instituto'])){
                      
            return app(Pipeline::class)
                ->send($tree)
                ->through([
                    Type::class,
                    Order::class
                ])->thenReturn();

        } else {
            print("Nao tem escolas ");
            return $next($tree);
        }
    }
}
