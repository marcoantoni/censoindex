<?php

namespace App\Tree\Student;

use App\Curso;
use App\Decorators\Token;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;

class Course extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$tokens = $tree->getTokens();
		$messageCourse = false;
		$schoolsFound = session('schoolsFound');

		for ($i = 0; $i < count($tokens); $i++) {
			
			$course = Curso::where('NOME', '=', $tokens[$i])->first();

			if ($course) {

				$messageCourse = $course['NOME'];

				if ($schoolsFound == 0 || $schoolsFound == 1){
					$tree->setQuery($tree->getQuery()->where('CO_CURSO_EDUC_PROFISSIONAL', $course['CO_CURSO_EDUC_PROFISSIONAL']));
				} else {
					// Percorre o array $data da classe Answer
					// O indíce [0] armazena o nome da escola enquanto o [1] armazena o objeto da classe Builder que representa o número de alunos
					// Basta adicionar a restrição a consulta 
					foreach ($tree->answer->data as $key => $query) {
						$query[1]->where('CO_CURSO_EDUC_PROFISSIONAL', $course['CO_CURSO_EDUC_PROFISSIONAL']);
					}
				}
				break;
			}
		}

		session(['courseName' => $messageCourse ]);

		return $next($tree);

	}
}
