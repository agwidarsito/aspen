<?php

namespace Nodes;

/**
 * Class SubtractionNode
 * @package Nodes
 */
class SubtractionNode extends BinaryNode  {

    public function symbol(){
        return "-";
    }

    public function evaluate($lookupTable){
        return $this->left->evaluate($lookupTable) - $this->right->evaluate($lookupTable);
    }

    public function name(){
        return "Sub";
    }

}