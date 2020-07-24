<?php

namespace App\Tree;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use App\Municipio;
use App\UF;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Request;

class Location extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
     
        $cityId = null;
        $condition = [];
        $foundCity = false;
        $lookingState = false;
        $reseultSet = null;
        $uf = null;
        $ufId = null;
        
        // variavel de controle para saber se foi especificado uma localizacao (uf) dentro da busca.
        // Se nao encontrou uma uf, e preciso buscar a localizacao
        $ufPresent = false;
        
        /* No melhor caso, foi digitado a cidade e UF. Primeiro procura a UF */
        foreach ($tree->getEntityies() as $entity) {
           if ($entity->getType() == EntityType::LOCATION) {
                $entityName = $entity->getName();    
                if (strlen($entityName) == 2){
                    $uf = $entityName;
                    $ufPresent = true;
                } 
            }
        }

        /* Se nao encontrou uma UF, irá buscar pela localizacao do IP para restringir a busca do municipio */
        if (!$ufPresent) {
            $uf = $this->getLocalizationByIP();
        }
        $entityies = array();
        // procura o municipio
        foreach ($tree->getEntityies() as $key => $entity) {
            $entityName = $entity->getName();
            $query = Municipio::where('uf', '=', $uf)->where('nome', 'like', '%'.$entityName.'%')->first();
            // se a consulta retornou um resultado, testa se a expressao está presente na sentenca
            if ($query) {
                $search = $query['NOME'];
                if(preg_match("/{$search}/i", $tree->sentence)) {
                    $cityId = $query['CO_MUNICIPIO'];
                    $foundCity = true;
                } 
            } else {
               // print ('entityName = ' . $entityName);
                $entityies[] = $entity;
            }  
        }

        $tree->setEntityies($entityies);
            
        // se nao encontrou uma cidade, a pesquisa é pelo estado
        if (! $cityId) {
            $reseultSet = UF::where('NO_UF', '=', $uf)->first();
            $lookingState = true; 
        } 
 
        if ($lookingState){
            $condition = array(
                'field' => 'CO_UF',
                'operator' => '=',
                'value' => $reseultSet['co_uf']
            );
        } else {
            $condition = array(
               'field' => 'CO_MUNICIPIO',
                'operator' => '=',
                'value' => $cityId
            );
        } 

        $tree->addCondition($condition);  

        return $next($tree);

    }
    
    /**
    *
    * Busca a Unidade Federativa a que um IP pertence
    * Faz uma requisicao para a API  ip-api
    * @see      https://ip-api.com/docs/api:json
    * @author   Marco Antoni <marco.antoni910@gmail.com>
    * @return   String
    *
    */
    public function getLocalizationByIP(){
        $ip = '170.244.220.26'; // Request::ip()
        $url = 'http://ip-api.com/json/' . $ip . '?fields=region';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);  
        $data = json_decode($output);

        return $data->region;   
    }
}
