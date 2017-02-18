<?php

require_once "vendor/autoload.php";

$nodeRegistry = new \Evolution\NodeRegistry();
$mutator = new \Evolution\Mutator($nodeRegistry);

$simulator = new \Evolution\Simulator($nodeRegistry, $mutator);

$initialMembers = 5;
for($i = 0; $i < $initialMembers; $i++){
    $simulator->addFirstGenerationMember([1,[1,1]]);
}

$lookupTable = [];
$simulator->setFitnessFunction(function(\Nodes\AbstractNode $abstractNode) use ($lookupTable){
    return abs(100 - $abstractNode->evaluate($lookupTable));
});

$stopCriteria = function(\Nodes\AbstractNode $abstractNode) use ($lookupTable){
    return (abs(100 - $abstractNode->evaluate($lookupTable)) < 1);
};

$simulator->simulate(100, $lookupTable, $stopCriteria);