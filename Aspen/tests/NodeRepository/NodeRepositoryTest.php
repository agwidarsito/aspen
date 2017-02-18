<?php

namespace tests\NodeRepository;

use Evolution\NodeRegistry;
use Nodes\AbstractNode;
use Nodes\VariableNode;

class NodeRepositoryTest extends \PHPUnit_Framework_TestCase{

    /** test_addVariableNode_method_works
     *
     *  Ensures that one can add variable nodes into the registry.
     *
     */
    public function test_addVariableNode_method_works(){
        $registry = new NodeRegistry();

        $value = 5;
        $name = "X";
        $registry->addVariableNode($name);

        $reflectionObject = new \ReflectionObject($registry);
        $reflectedProperty = $reflectionObject->getProperty("variableNodes");
        $reflectedProperty->setAccessible(TRUE);

        $nodes = $reflectedProperty->getValue($registry);

        $this->assertTrue(is_array($nodes));

        /** @var VariableNode $testNode */
        $testNode = $nodes[0];
        $this->assertEquals($value, $testNode->evaluate(["X" => $value]));
        $this->assertEquals($name, $testNode->name());
    }

    /** test_addVariableNode_method_works_and_uses_references
     *
     *  Ensures that one can add variable nodes into the registry and the
     *  variable is added by reference.
     *
     */
    public function test_addVariableNode_method_works_and_uses_references(){
        $registry = new NodeRegistry();

        $value = 5;
        $name = "X";
        $registry->addVariableNode($name);

        $reflectionObject = new \ReflectionObject($registry);
        $reflectedProperty = $reflectionObject->getProperty("variableNodes");
        $reflectedProperty->setAccessible(TRUE);

        $nodes = $reflectedProperty->getValue($registry);

        /** @var VariableNode $testNode */
        $testNode = $nodes[0];
        $this->assertEquals($value, $testNode->evaluate(["X" => $value]));

        /** Change the value! */
        $value = 10;

        $testNode = $nodes[0];
        $this->assertEquals($value, $testNode->evaluate(["X" => $value]));
        $this->assertEquals($name, $testNode->name());
    }

    public function test_randomTree_method(){
        $registry = new NodeRegistry();

        $registry->addVariableNode("X");

        /** @var AbstractNode $tree */
        $tree = $registry->randomTreeFromExpr([1,[1,[1,1]]]);
        echo $tree->draw();

    }
}