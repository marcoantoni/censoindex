<?php

namespace App\Tree;

use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\Entity\Type as EntityType;
use App\Municipio;
use App\UF;


class Location extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
     
        $city = null;
        $uf = null;
        $condition = [];
        $lookingState = false;

        foreach ($tree->getEntityies() as $entity) {
           if ($entity->getType() == EntityType::LOCATION) {
                $entityName = $entity->getName();
                              
                if (strlen($entityName) == 2){
                    $uf = $entityName;
                } else {
                    $city = $entityName;
                }
            }
        }

        if ($city == null){
            foreach ($tree->getEntityies() as $entity) {
               if ($entity->getType() == EntityType::OTHER) {
                    $entityName = $entity->getName();
                    $city = $entityName;
                    break;
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
}
