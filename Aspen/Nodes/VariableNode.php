<?php

namespace Nodes;

/**
 * Class TerminalNode
 * @package Nodes
 */
class VariableNode extends TerminalNode  {

    protected $name;

    public function __construct($name){
        $this->name = $name;
    }

    public function evaluate($lookupTable){
        return $lookupTable[$this->name()];
    }

    public function name(){
        return $this->name;
    }

}