<?php

namespace Nodes;

/**
 * Class TerminalNode
 * @package Nodes
 */
abstract class TerminalNode extends AbstractNode {

    public function hasChildren(){
        return FALSE;
    }

    public function draw(){
        return $this->name();
    }

    public function children(){
        return [];
    }

    public function recursiveReplace($needle, $replacement){
        ;
    }

    public function expression(){
        return $this->name();
    }

}