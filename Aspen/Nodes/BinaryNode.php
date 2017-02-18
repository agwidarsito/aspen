<?php

namespace Nodes;

/**
 * Class BinaryNode
 * @package Nodes
 */
abstract class BinaryNode extends AbstractNode {

    protected $left;

    protected $right;

    public function __construct(AbstractNode $left, AbstractNode $right){
        $this->left = $left;
        $this->right = $right;
    }

    public function hasChildren(){
        return TRUE;
    }

    public function draw(){
        return $this->name() . "(" . $this->left->draw() . ", " . $this->right->draw() . ")";
    }

    public function children(){
        $leftChildren = $this->left->children();
        $rightChildren = $this->right->children();

        return array_merge($leftChildren, $rightChildren, [&$this->left, &$this->right]);
    }

    public function recursiveReplace($needle, $replacement){
        if ($this->left === $needle){
            $this->left = $replacement;
        }

        if ($this->right === $needle){
            $this->right = $replacement;
        }

        $this->left->recursiveReplace($needle, $replacement);
        $this->right->recursiveReplace($needle, $replacement);
    }

}