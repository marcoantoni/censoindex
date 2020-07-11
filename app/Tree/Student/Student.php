<?php


namespace App\Tree\Student;

use App\Decorators\Token;
use App\Matricula;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\DependencyEdge\Label;
use Illuminate\Pipeline\Pipeline;


class Student extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
      
        if (preg_match('/alun|estudante|matricula/', $tree->sentence)) {
            $tree->query = Matricula::query();
            

            return app(Pipeline::class)
                ->send($tree)
                ->through([
                    School::class,
                    Type::class,
                ])->thenReturn();    
        } else {
            return $next($tree);
        }
    }
}
