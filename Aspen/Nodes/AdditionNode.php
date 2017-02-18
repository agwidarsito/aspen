<?php

namespace Nodes;

/**
 * Class AdditionNode
 * @package Nodes
 */
class AdditionNode extends BinaryNode  {

    public function symbol(){
        return "+";
    }

    public function evaluate($lookupTable){
        return $this->left->evaluate($lookupTable) + $this->right->evaluate($lookupTable);
    }

    public function name(){
        return "Add";
    }

}