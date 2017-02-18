<?php

namespace Nodes;

/**
 * Class AbstractNode
 * @package Nodes
 */
abstract class AbstractNode{

    abstract public function evaluate($lookupTable);

    abstract public function name();

    abstract public function hasChildren();

    abstract public function draw();

    abstract public function symbol();

    abstract public function expression();

    abstract public function children();

    abstract public function recursiveReplace($needle, $replacement);

    /**
     * @return AbstractNode
     */
    public function randomChild(){
        $children = $this->children();
        if (empty($children)){
            return NULL;
        }

        return $children[array_rand($children, 1)];
    }

    public function depth(){
        return count($this->children());
    }

    /**
     * @return AbstractNode
     */
    public function deepCopy(){
        return unserialize(serialize($this));
    }

}