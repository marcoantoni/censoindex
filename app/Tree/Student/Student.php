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
        $tree->answer->data = array();  // define como array pois a resposta pode retornar mais de uma escola
        $tree->answer->setResponseTable(Answer::STUDENT);
        $tree->answer->addUserMessage(
            Answer::WARNING, 
            'Esses dados não incluem alunos de nível <b>Superior</b>'
        );

        app(Pipeline::class)
            ->send($tree)
            ->through([
                School::class,
                Phases::class,
                Year::class,
                TransportType::class,
                Course::class,
            ])->thenReturn();                

        return $tree;   
    }
}
