<?php

namespace Nodes;

/**
 * Class DivisionNode
 * @package Nodes
 */
class DivisionNode extends BinaryNode  {

    public function symbol(){
        return "/";
    }

    public function evaluate($lookupTable){
        if ($this->right->evaluate($lookupTable) < 0.0001){
            return 0;
        }

        return $this->left->evaluate($lookupTable) / $this->right->evaluate($lookupTable);
    }

    public function name(){
        return "Div";
    }

}