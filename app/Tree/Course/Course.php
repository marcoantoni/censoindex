<?php

namespace App\Tree\Course;

use App\Curso;
use App\Matricula;
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

		$tree->setQuery(Curso::query());

		// Vai para o App\Tree\Student\School para verificar se a pergunta possui uma escola
        app(Pipeline::class)
            ->send($tree)
            ->through([
                School::class,
            ])->thenReturn();    

		if (session('CO_ENTIDADE')){
			$coursesId = Matricula::distinct('CO_CURSO_EDUC_PROFISSIONAL')
				->where('CO_ENTIDADE', '=', session('CO_ENTIDADE') )
				->get(['CO_CURSO_EDUC_PROFISSIONAL'])
				->toArray();
		}else{
			$coursesId = Matricula::distinct('CO_CURSO_EDUC_PROFISSIONAL')
				->where('CO_MUNICIPIO', '=', $cityId)
				->get(['CO_CURSO_EDUC_PROFISSIONAL'])
				->toArray();
		}
		
		$tree->setQuery(
			DB::table('cursos')
				->whereIn('CO_CURSO_EDUC_PROFISSIONAL', array_values($coursesId))
				->orderBy('NOME', 'ASC')
		);
		
		return $next($tree);
	}	
}
