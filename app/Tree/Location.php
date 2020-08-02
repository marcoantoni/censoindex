<?php

namespace App\Tree;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use App\Municipio;
use App\Matricula;
use App\UF;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use Request;
use Session;

class Location extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
     
        $cityId = null;
        $condition = [];
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
                } else {
                    $entityies[] = $entity;
                }
            }
        }

        /* Se nao encontrou uma UF, irá buscar pela localizacao do IP para restringir a busca do municipio */
        if (!$ufPresent) {
            $uf = $this->getLocalizationByIP();
        }

        // procura o municipio
        foreach ($tree->getEntityies() as $key => $entity) {
            $entityName = $entity->getName();
            $query = Municipio::where('uf', '=', $uf)->where('nome', 'like', "$entityName")->first();

            // se a consulta retornou um resultado
            if ($query) {
                $cityId = $query['CO_MUNICIPIO'];
                session(['CO_MUNICIPIO' => $cityId ]);
                session(['NOME_MUNICIPIO' => $query['NOME'] ]);
                $uf = $query['UF'];
                break;
            }
        }
        
        // Busca e armazena o CO_UF e o NO_UF na sessão
        // Pode ser necessário para buscar estatistícas do estado e necessário para apresentação da resposta personalizada das perguntas 
        $reseultSet = UF::where('no_uf', '=', $uf)->first();
        session(['CO_UF' => $reseultSet['co_uf'] ]);
        session(['NO_UF' => $reseultSet['no_uf'] ]);

        if (! $cityId){
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

        //$tree->setQuery($tree->getQuery()->where($condition['field'], $condition['value']));
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
