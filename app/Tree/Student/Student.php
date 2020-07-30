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
        
        $tree->setQuery(Matricula::query());
        //$tree->query = Matricula::query();
        $tree->answer->data = array();
        $tree->answer->setResponseTable(Answer::STUDENT);

        return app(Pipeline::class)
            ->send($tree)
            ->through([
                School::class,
                TransportType::class,
                Course::class,
            ])->thenReturn();       
    }
}
