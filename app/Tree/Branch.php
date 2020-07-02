<?php


namespace App\Tree;

use Closure;

abstract class Branch {
    abstract function handle(DecisionTree $tree, Closure $next): DecisionTree;
}
