<?php

require_once "../vendor/autoload.php";

$nodeA = new \Nodes\AdditionNode(new \Nodes\TerminalNode(4),
    new \Nodes\AdditionNode(new \Nodes\TerminalNode(1), new \Nodes\TerminalNode(2)));

$mutator = new \Evolution\Mutator(1);
$mutator->mutate($nodeA);

echo $nodeA->draw();