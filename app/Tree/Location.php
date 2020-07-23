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
     
        $city = null;
        $uf = null;
        $condition = [];
        $lookingState = false;
        // variavel de controle para saber se foi especificado uma localizacao (uf) dentro da busca.
        // Se nao encontrou uma uf, e preciso buscar a localizacao
        
        $ufPresent = false;

        // melhor caso: usuario informou cidade e uf
        foreach ($tree->getEntityies() as $entity) {
           if ($entity->getType() == EntityType::LOCATION) {
                $entityName = $entity->getName();
                              
                if (strlen($entityName) == 2){
                    $uf = $entityName;
                    $ufPresent = true;
                } else {
                    $city = $entityName;
                }
            }
        }

        // se nao encontrou um estado irá buscar pela localizacao do IP
        if (!$ufPresent) {
            $uf = $this->getLocalizationByIP();
        }
        
        if ($city == null){
            foreach ($tree->getEntityies() as $entity) {
                $query = Municipio::where('uf', '=', $uf)->where('nome', 'like', '%'.$entity->getName().'%')->first(); 
                $search = $query['NOME'];
                // se a consulta retornou resultado
                if ($query) {
                    // testa se o nome da cidade está presente na sentenca
                    if(preg_match("/{$search}/i", $tree->sentence)) {
                        $city = $search;
                        break;
                    }   
                }
            }
        }
            
        // se nao encontrou uma cidade, a pesquisa é pelo estado
        if ($city == null) {
            $reseultSet = UF::where('NO_UF', '=', $uf)->first();
            $lookingState = true; 
        } elseif ($uf) {
            $reseultSet = Municipio::where([
                ['nome', '=', $city],
                ['uf', '=', $uf]
            ])->first();
        } else {
            $reseultSet = Municipio::where('nome', $city)->first();
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
                'value' => $reseultSet['id']
            );
        } 

        $tree->addCondition($condition);  
        
        return $next($tree);
    }

    public function getLocalizationByIP(){
        $ip = '170.244.220.26';
        // documentacao -> https://ip-api.com/docs/api:json
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
