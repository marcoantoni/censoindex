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
        $tree->answer->data = array();
        $tree->answer->setResponseTable(Answer::STUDENT);
        $tree->answer->addUserMessage(
                Answer::WARNING, 
                'Esses dados não incluem alunos de nível <b>Superior</b>'
            );

        return app(Pipeline::class)
            ->send($tree)
            ->through([
                School::class,
                TransportType::class,
                Course::class,
            ])->thenReturn();       
    }
}
