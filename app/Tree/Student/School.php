<?php

namespace App\Tree\Student;

use App\Escola;
use App\Matricula;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Session;

class School extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$cityId = session('CO_MUNICIPIO');	// essa variável é inicializada em app\Tree\Location.php
		$school = null;
		$schoolId = null;
		$schoolsFound = 0;
		$condition = null;
		$no_entidade = '';	// escrito assim pois se refere ao atribudo do banco de dados

		// Inicializa as variáveis de sessão com valores default
		session(['NO_ENTIDADE' => false]);
		session(['CO_ENTIDADE' => false]);
		session(['schoolsFound' => 0]);

		foreach ($tree->getEntityies() as $entity) {

			$entityName = $entity->getName();
			
			// Ignore a entidade cujo nome é o mesmo que o municipio
			// Evita que a pesquisa por "quais escolas tem em Rolante/RS" retorne o ifRS devido ao like na consulta
			if ((strcasecmp(session('NOME_MUNICIPIO'), $entityName) != 0) && (strcasecmp(session('NO_UF'), $entityName) != 0) ) {
			
				$school = Escola::where('CO_MUNICIPIO', $cityId)->where('NO_ENTIDADE', 'like', '%'.$entityName.'%')->first();
				
				if ($school){
					$no_entidade = $school['NO_ENTIDADE'];
					$schoolId = $school['CO_ENTIDADE'];
										
					$builder = Matricula::where('CO_ENTIDADE', $school['CO_ENTIDADE'] );
					$arrData = array($no_entidade, $builder);
					$tree->answer->data[] = $arrData;
					$schoolsFound++;
				}	
			} 
		}

		if ($schoolsFound == 1){
			session(['NO_ENTIDADE' => $no_entidade ]);
			session(['CO_ENTIDADE' => $schoolId ]);
			$tree->setQuery($tree->getQuery()->where('CO_ENTIDADE', $schoolId));
		}

		session(['schoolsFound' => $schoolsFound]);

		// Busca as estatistícas de matrículas da cidade e estado
		// Se encontrou uma escola, busca as informacoes do município
//		if ($school){
//			$tree->answer->statistics['city'] = Matricula::where('CO_MUNICIPIO', $cityId)->count();
//		} 

		// Se não encontrou um município, a pesquisa esta sendo feita pelo estado
		// Não e necessário as estatistícas do estado
//		if (session('CO_MUNICIPIO')) {
//			$tree->answer->statistics['state'] = Matricula::where('CO_UF', session('CO_UF') )->count();
//		}

		return $next($tree);
	}
}
