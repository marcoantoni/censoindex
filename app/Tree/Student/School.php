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
use Google\Cloud\Language\V1\Entity\Type as EntityType;

class School extends Branch {
	
	function handle(DecisionTree $tree, Closure $next): DecisionTree {
		
		$cityId = session('CO_MUNICIPIO');	// essa variável é inicializada em app\Tree\Location.php
		$stateId = session('CO_UF');	// essa variável é inicializada em app\Tree\Location.php
		$school = null;
		$schoolId = null;
		$schoolsFound = 0;
		$no_entidade = '';	// escrito assim pois se refere ao atribudo do banco de dados

		foreach ($tree->getEntityies() as $entity) {
			if ($entity->getType() != EntityType::NUMBER) {
				$entityName = $entity->getName();
				
				/* Ignore a entidade cujo nome é o mesmo que o municipio.
				 * Evita que a pesquisa por "quais escolas tem em Rolante/RS" retorne o ifRS devido ao like na consulta.
				 * Ignora as palavras creche, infantil, ensino, fundamental, médio pois nesse aplicação, ao pesquisar escolas,
				 * pode-se considerar essas palavras como stop words.
				*/
				if ((strcasecmp($tree->removeAccents(session('NOME_MUNICIPIO')), $entityName) != 0) && 
					(strcasecmp(session('NO_UF'), $entityName) != 0) && 
					(strcasecmp('creche', $entityName) != 0) && 
					(strcasecmp('escola', $entityName) != 0) && 
					(strcasecmp('colegio', $entityName) != 0) && 
					(strcasecmp('ensino', $entityName) != 0) && 
					(strcasecmp('educacao infantil', $entityName) != 0) && 
					(strcasecmp('ensino fundamental', $entityName) != 0) && 
					(strcasecmp('ensino medio', $entityName) != 0) && 
					(strcasecmp('estadual', $entityName) != 0) && 
					(strcasecmp('municipal', $entityName) != 0) && 
					(strcasecmp('fundamental', $entityName) != 0) &&
					(strcasecmp('infantil', $entityName) != 0) ){

					$school = Escola::where('CO_MUNICIPIO', $cityId)->where('NO_ENTIDADE', 'like', "%$entityName%")->first();
					
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
		}

		if ($schoolsFound == 1){
			session(['NO_ENTIDADE' => $no_entidade ]);
			session(['CO_ENTIDADE' => $schoolId ]);
			$tree->setQuery($tree->getQuery()->where('CO_ENTIDADE', $schoolId));
		}

		session(['schoolsFound' => $schoolsFound]);

		// Busca a quantidade de alunos da cidade/estado
		$tree->answer->statistics->generate();

		return $next($tree);
	}
}
