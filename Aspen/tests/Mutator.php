<?php

namespace tests\NodeRepository;

use Evolution\Mutator;
use Evolution\NodeRegistry;
use Nodes\AdditionNode;
use Nodes\ConstantNode;
use Nodes\SubtractionNode;

class MutatorTest extends \PHPUnit_Framework_TestCase{

    public function test_mutation_crossover_works(){
        $rightNode = new SubtractionNode(new ConstantNode(1), new AdditionNode(
            new ConstantNode(1), new ConstantNode(2)
        ));

        $mutator = new Mutator(new NodeRegistry());
        $child = $mutator->crossOver($rightNode, $rightNode);

        echo "Left node: " . $rightNode->draw() . PHP_EOL;
        echo "Right node: " . $rightNode->draw() . PHP_EOL;
        echo "Subtree of right node: " . $child["Subtree"]->draw() . PHP_EOL;
        echo "Insertion point of left node: " . $child["InsertionPoint"]->draw() . PHP_EOL;
        echo "Child: " . $child["Child"]->draw();
    }

    public function test_mutation_mutate_works(){
        $rightNode = new SubtractionNode(new ConstantNode(1), new AdditionNode(
            new ConstantNode(1), new ConstantNode(2)
        ));

        $mutator = new Mutator(new NodeRegistry());
        $result = $mutator->mutate($rightNode);

        echo "Original: " . $rightNode->draw() . PHP_EOL;
        echo "Mutated: " . $result->draw() . PHP_EOL;
    }

}