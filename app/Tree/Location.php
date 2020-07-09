<?php


namespace App\Tree;

use App\Decorators\Token;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\DependencyEdge\Label;
use Google\Cloud\Language\V1\Entity\Type as EntityType;

use App\Municipio;
use App\UF;
use DB;

class Location extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
     
        $city = null;
        $uf = null;
        $condition = [];

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
        // if not found a city is looking for a state
        if ($city == null) {            
            $reseultSet = UF::where('NO_UF', '=', $uf)->first();
            
            $condition = array(
               'field' => 'CO_UF',
                'operator' => '=',
                'value' => $reseultSet['co_uf']
            );
        // found a city
        } else if ($uf) { 
            $reseultSet = Municipio::where([
                ['nome', '=', $city],
                ['uf', '=', $uf]
            ])->first();
        } else {
            $reseultSet = Municipio::where('nome', $city)->first();
        } 
           
        $condition = array(
           'field' => 'CO_MUNICIPIO',
            'operator' => '=',
            'value' => $reseultSet['id']
        );

        $tree->addCondition($condition);  

        return $next($tree);
    }
}
