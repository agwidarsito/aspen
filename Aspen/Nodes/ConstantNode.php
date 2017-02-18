<?php

namespace Nodes;

/**
 * Class TerminalNode
 * @package Nodes
 */
class ConstantNode extends TerminalNode  {

    private $value;

    public function symbol(){
        return $this->value();
    }

    public function __construct($value){
        $this->value = $value;
    }

    public function evaluate($lookupTable){
        return $this->value;
    }

    public function name(){
        return $this->value;
    }

    public function updateValue($value){
        $this->value = $value;
    }

    public function value(){
        return $this->value;
    }

}