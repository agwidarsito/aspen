<?php

namespace Evolution;

use Nodes\AbstractNode;
use Nodes\AdditionNode;
use Nodes\ConstantNode;
use Nodes\DivisionNode;
use Nodes\MultiplicationNode;
use Nodes\SubtractionNode;
use Nodes\VariableNode;

/**
 * Class NodeRegistry
 * @package Evolution
 */
class NodeRegistry{

    private $variableNodes = [];

    private $binaryNodes = array(
        AdditionNode::class,
        SubtractionNode::class,
        MultiplicationNode::class,
        DivisionNode::class
    );

    public function addVariableNode($name){
        $this->variableNodes[] = new VariableNode($name);
    }

    public function variableNodes(){
        return $this->variableNodes;
    }

    public function randomConstantNode(){
        return new ConstantNode(rand(0, 100));
    }

    public function randomTerminalNode($prVariableNode){
        if (rand(0, 10)/10 < $prVariableNode){
            try{
                return $this->randomVariableNode();
            }catch(\Exception $e) {
                return $this->randomConstantNode();
            }
        }

        return $this->randomConstantNode();
    }

    public function randomVariableNode(){
        if (0 === count($this->variableNodes)){
            throw new \Exception("No variable nodes added!");
        }

        $nodes = $this->variableNodes();
        return $nodes[array_rand($nodes, 1)];
    }

    public function binaryNodes(){
        return $this->binaryNodes;
    }

    public function randomBinaryNode(){
        $nodes = $this->binaryNodes();
        return $nodes[array_rand($nodes, 1)];
    }

    /**
     * @param $treeExpressionLanguage
     * @return AbstractNode
     */
    public function randomTreeFromExpr($treeExpressionLanguage){
        $that = $this;

        $genStruct = function($array) use (&$genStruct, $that){
            $prVariableNode = 0.2; //Converse: prConstantNode

            if (is_array($array)){
                /** Must be a binary node */
                $left = $genStruct($array[0]);
                $right = $genStruct($array[1]);

                $nodeName = $that->randomBinaryNode();
                $newNode = new $nodeName($left, $right);
                return $newNode;
            }else{
                $rand = rand(0, 10) / 10;
                if ($rand < $prVariableNode){
                    try{
                        return $this->randomVariableNode();
                    }catch(\Exception $e){
                        /** If no variable nodes exist */
                        return $this->randomConstantNode();
                    }
                }
                return $this->randomConstantNode();
            }
        };

        return $genStruct($treeExpressionLanguage);
    }

}