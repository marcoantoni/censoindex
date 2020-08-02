<?php

namespace App\Tree;

use App\Matricula;
use App\Model\MatriculaCache;

class Statistics {

    public $stats; 
    // array contendo instâncias do query builder
    public $query;
    // Variavel contendo uma instancia da classe MatriculaCache
    // Caso a informação não esteja em cache, será usada para inserir o valor na tabela matriculas_cache
    public $fields;

    public function __construct(){
        $this->stats = array();
        $this->fields = array();
        $this->query = array(['city', 'state']);
    }

    /* 
     * Busca a quantidade de matrículas da cidade e do estado
     * É criado dois objetos para executar a consulta das inforamções: Uma para busca na tabela 
     * matriculas_cache e outro na tabela matricula. Por isso as condições são as mesmas
    */
    public function generate(){
    
        // Se encontrou há alguma escola, busca as informações do município
        if (session('schoolsFound') >= 1){
            $cityStats = array();   // cria um array de estatísticas para a cidade
            // query para buscar na tabela matriculas_cache
            $cityStats['cache'] = MatriculaCache::where('CO_MUNICIPIO', session('CO_MUNICIPIO'));
            // query para buscar na tabela matriculas
            $cityStats['nocache'] = Matricula::where('CO_MUNICIPIO', session('CO_MUNICIPIO'));
            
            $this->query['city'] = $cityStats;
        } 

        // Se não encontrou um município, a pesquisa esta sendo feita pelo estado
        if (session('CO_MUNICIPIO')) {
            // mesma lógica usada acima
            $stateStats = array();
            $stateStats['cache'] = MatriculaCache::where('CO_UF', session('CO_UF')); 
            $stateStats['nocache'] = Matricula::where('co_uf', session('CO_UF'));
            $this->query['state'] = $stateStats;
        }
    }

    /* 
     * Executa a query
    */
    public function execute(){
        
        // Se encontrou há alguma escola, busca as informações do município
        if (session('schoolsFound') > 0){
            // Busca o objeto builder para exeutar a consulta no cache
            $cacheQuery =  $this->query['city']['cache'];
            
            $result = $cacheQuery->first();
            
            // Se a consulta retornou algo, está no cache
            if ($result){
                $this->stats['city'] = $result['QUANTIDADE'];
            } else {
                // Não encontrou no cache
                // Busca o objeto builder para exeutar a consulta no cache
                $noCacheQuery = $this->query['city']['nocache'];
                $result = $noCacheQuery->count();
                $this->stats['city'] = $result;
                // Salva no cache
                $cache = new MatriculaCache;
                $cache->CO_MUNICIPIO = session('CO_MUNICIPIO');
                $cache->QUANTIDADE = $result;
                
                // Laço de repetição para adicionar as condições de busca de outras classes ao cache
                foreach ($this->fields as $key => $value) {
                    $cache->$key = $value;
                }
                $cache->save();
            }
        }

        // Se não encontrou um município, a pesquisa esta sendo feita pelo estado
        // Não e necessário as estatistícas do estado
        if (session('CO_MUNICIPIO')) {
            $cacheQuery = $this->query['state']['cache'];
            $result = $cacheQuery->first();
            
            if ($result){
                $this->stats['state'] = $result['QUANTIDADE'];
            } else {
                $noCacheQuery = $this->query['state']['nocache'];
                $result = $noCacheQuery->count();
                $this->stats['state'] = $result;

                $cache = new MatriculaCache;
                $cache->CO_UF = session('CO_UF');
                $cache->QUANTIDADE = $result;
  
                foreach ($this->fields as $key => $value) {
                    $cache->$key = $value;
                }
                $cache->save();
            }
        }
    }
}