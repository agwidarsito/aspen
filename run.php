<?php

require_once "vendor/autoload.php";

$nodeRegistry = new \Evolution\NodeRegistry();
$nodeRegistry->addVariableNode("X");

$mutator = new \Evolution\Mutator($nodeRegistry);

$simulator = new \Evolution\Simulator($nodeRegistry, $mutator);

$initialMembers = 5;
for($i = 0; $i < $initialMembers; $i++){
    $simulator->addFirstGenerationMember([1,[1,"*"]]);
    $simulator->addFirstGenerationMember([1,"*"]);
    $simulator->addFirstGenerationMember([1,[1,[1,"*"]]]);
}

$valueOfX = 0;
$lookupTable = ["X" => &$valueOfX];
$simulator->setFitnessFunction(function(\Nodes\AbstractNode $abstractNode) use ($lookupTable, &$valueOfX){
    $difference = 0;
    for($i = 0; $i < 100; $i++){
        $x = (pi()/2) / 100 * $i;
        $valueOfX = $x;

        $realValue = sin($x);

        $difference += abs($abstractNode->evaluate($lookupTable) - $realValue);
    }

    if ($abstractNode->depth() > 30){
        $difference += 1000;
    }

    return $difference;
});

$stopCriteria = function(\Nodes\AbstractNode $abstractNode) use ($lookupTable, &$valueOfX){
    $difference = 0;
    for($i = 0; $i < 100; $i++){
        $x = (pi()/2) / 100 * $i;
        $valueOfX = $x;

        $realValue = sin($x);

        $difference += abs($abstractNode->evaluate($lookupTable) - $realValue);
    }

    if ($abstractNode->depth() > 30){
        $difference += 1000;
    }

    return ($difference < 2);
};

$simulator->simulate(5000, $lookupTable, $stopCriteria);