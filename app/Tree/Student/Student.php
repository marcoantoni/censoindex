<?php


namespace App\Tree\Student;

use App\Matricula;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Pipeline\Pipeline;


class Student extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
        
        if (preg_match('/alun|estudante|matricula/', $tree->sentence)) {
            $tree->query = Matricula::query();
            $tree->answer->data = array();
            // Sempre que a execução vier para este galho, a resposta será numérica
            $tree->answer->setResponseType(Answer::NUMBER);
            
            return app(Pipeline::class)
                ->send($tree)
                ->through([
                    School::class,
                    Type::class,
                    Course::class,
                ])->thenReturn();    
        } else {
            return $next($tree);
        }
    }
}
