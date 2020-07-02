<?php


namespace App\Tree\Location;

use App\Decorators\Token;
use App\Tree\Answer;
use App\Tree\Branch;
use App\Tree\DecisionTree;
use Closure;
use Google\Cloud\Language\V1\DependencyEdge\Label;
use Google\Cloud\Language\V1\Entity\Type as EntityType;

use App\Municipio;

class Location extends Branch {

    function handle(DecisionTree $tree, Closure $next): DecisionTree {
     
        $city = '';
        $uf = '';
        $condition = [];

        foreach ($tree->getEntityies() as $entity) {
           if ($entity->getType() == EntityType::LOCATION) {
                $entityName = $entity->getName();
                              
                if (strlen($entityName) == 2){
                    $uf = $entityName;
                } else {
                    $city = $entityName;
                }
            } else {
                print('nao tem localidade');
            }
        }

       // if (!$city) {
        //    print('Não encontrou uma cidade');
       // }

        $reseultSet = Municipio::where([
            ['nome', '=', $city],
            ['uf', '=', $uf]
        ])->first();
      
        //print ('<br>Cidade=' . $city .' RS: ' . $uf);
        //print('<br>Municipio id: ' . $reseultSet['id']);
      
        $condition = array(
           'field' => 'CO_MUNICIPIO',
            'operator' => '=',
            'value' => $reseultSet['id']
        );

        $tree->addCondition($condition);  

        return $next($tree);
    }
}
