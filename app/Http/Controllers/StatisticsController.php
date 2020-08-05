<?php

namespace App\Http\Controllers;

use App\Model\MatriculaCache;
use Illuminate\Http\Request;

class StatisticsController extends Controller {
    
    // Retorna o total de Matriculas de uma cidade e estado
    public function getStatistics(int $idState, int $idCity = null){
    	$state = MatriculaCache::where('CO_UF', $idState)->first();
    	$city = MatriculaCache::where('CO_MUNICIPIO', $idCity)->first();
    	$data = [
    		'city' => $city['QUANTIDADE'],
    		'state' => $state['QUANTIDADE'], 
    		'message' => 'Número total de alunos'
    	];
    	return $data;
    }
}
