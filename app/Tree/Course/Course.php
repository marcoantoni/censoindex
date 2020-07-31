<?php

namespace App\Tree\Course;

use App\Curso;
use App\Matricula;
use App\Escola;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use App\Tree\Student\School;
use Closure;
use DB;
use Illuminate\Pipeline\Pipeline;
use Session;

class Course extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {

		$cityId = session('CO_MUNICIPIO');	// essa variável é inicializada em app\Tree\Location.php
		$courseName = null;
		/* 
		 * Responde sobre quais escolas ofertam um curso tecnico. Nesse tipo de pergunta há o pronome interrogativo "onde".
		 * Procura pelo expressão "tecnico" na pergunta e realiza as consultas na tabela Cursos até encontrar um curso
		 */
		if(preg_match("/onde/i", $tree->sentence)) {
			$tokens = $tree->getTokens();
			// percorre todos os tokens buscando se ele é um curso
			foreach ($tokens as $key => $token) {

				$course = Curso::where('NOME', $tokens[$key])->first();

				if ($course) {
					$courseName = $course['NOME'];
					
					$SchoolsId = Matricula::distinct('CO_ENTIDADE')
						->where('CO_CURSO_EDUC_PROFISSIONAL', $course['CO_CURSO_EDUC_PROFISSIONAL'] )
						->where ('CO_MUNICIPIO', $cityId)
						->get(['CO_ENTIDADE'])
						->toArray();

					// Realiza a consulta passando como parametro o resultado da consulta anterior
					$tree->setQuery(
						DB::table('escolas')
							->whereIn('CO_ENTIDADE', array_values($SchoolsId))
							->orderBy('NO_ENTIDADE', 'ASC')
					);
					$tree->answer->setResponseTable(Answer::SCHOOL);
				}
			}
		// Lista os cursos ofertados em uma cidade/escola
		} else {
			$tree->answer->setResponseTable(Answer::COURSE);
			$tree->setQuery(Curso::query());
			
			// Vai para o App\Tree\Student\School para verificar se a pergunta possui uma escola associada
	        app(Pipeline::class)
				->send($tree)
				->through([
					School::class,
				])->thenReturn();    

			// Se a pergunta encontrar uma entidade (escola), irá listar os cursos dessa escola
			if (session('CO_ENTIDADE')){
				$coursesId = Matricula::distinct('CO_CURSO_EDUC_PROFISSIONAL')
					->where('CO_ENTIDADE', '=', session('CO_ENTIDADE') )
					->get(['CO_CURSO_EDUC_PROFISSIONAL'])
					->toArray();
			// Lista os cursos ofertados na cidade
			}else{
				$coursesId = Matricula::distinct('CO_CURSO_EDUC_PROFISSIONAL')
					->where('CO_MUNICIPIO', '=', $cityId)
					->get(['CO_CURSO_EDUC_PROFISSIONAL'])
					->toArray();
			}
			
			// Realiza a consulta passando como parametro o resultado da consulta anterior
			$tree->setQuery(
				DB::table('cursos')
					->whereIn('CO_CURSO_EDUC_PROFISSIONAL', array_values($coursesId))
					->orderBy('NOME', 'ASC')
			);
		}
		session(['courseName' => $courseName]);
		return $next($tree);
	}	
}
