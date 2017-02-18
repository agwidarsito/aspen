<?php

namespace Evolution;

use Nodes\AbstractNode;
use Nodes\AdditionNode;
use Nodes\ConstantNode;
use Nodes\TerminalNode;

/**
 * Class Mutator
 * @package Evolution
 */
class Mutator{

    public $prBinaryNode = 0.3;
    public $prRandomVariableNode = 0.5;
    public $prChangeConstantValue = 0.4;

    private $nodeRegistry;

    public function __construct(NodeRegistry $nodeRegistry){
        $this->nodeRegistry = $nodeRegistry;
    }

    public function diceRoll(){
        return rand(0, 10) / 10;
    }

    public function mutate(AbstractNode $node){
        $copyNode = $node->deepCopy();



        /** Do we randomly add a binary node? */
        if ($this->diceRoll() <= $this->prBinaryNode){
            $newNodeClassName = $this->nodeRegistry->randomBinaryNode();
            $newNode = new $newNodeClassName(
                $this->nodeRegistry->randomTerminalNode($this->prRandomVariableNode),
                $this->nodeRegistry->randomTerminalNode($this->prRandomVariableNode)
            );

            $randomInsertionPoint = $copyNode->randomChild();
            if (NULL !== $randomInsertionPoint) {
                $copyNode->recursiveReplace($randomInsertionPoint, $newNode);
            }
        }

        /** Do we randomly change a constant by a value of 1? */
        $randomChild = $copyNode->randomChild();
        if ($randomChild instanceof ConstantNode){
             if ($this->diceRoll() < $this->prChangeConstantValue){
                 $randomChild->updateValue($randomChild->value() + rand(0, 10) / 10);
             }

            if ($this->diceRoll() < $this->prChangeConstantValue){
                $randomChild->updateValue($randomChild->value() + rand(0, 10) / 100);
            }

            if ($this->diceRoll() < $this->prChangeConstantValue){
                $randomChild->updateValue($randomChild->value() + rand(0, 10) / 1000);
            }
        }

        return $copyNode;
    }

    /**
     * @param AbstractNode $left
     * @param AbstractNode $right
     * @return array
     */
    public function crossOver(AbstractNode $left, AbstractNode $right){
        $copyOfLeft = $left->deepCopy();

        $randomSubTree = $right->randomChild();
        $randomInsertionPoint = $copyOfLeft->randomChild();

        $copyOfLeft->recursiveReplace($randomInsertionPoint, $randomSubTree);

        return array(
            "Subtree" => $randomSubTree,
            "InsertionPoint" => $randomInsertionPoint,
            "Child" => $copyOfLeft
        );
    }

}