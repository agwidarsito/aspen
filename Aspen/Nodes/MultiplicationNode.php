<?php

namespace Nodes;

/**
 * Class MultiplicationNode
 * @package Nodes
 */
class MultiplicationNode extends BinaryNode  {

    public function symbol(){
        return "*";
    }

    public function evaluate($lookupTable){
        return $this->left->evaluate($lookupTable) * $this->right->evaluate($lookupTable);
    }

    public function name(){
        return "Mul";
    }

}