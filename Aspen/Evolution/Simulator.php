<?php

namespace Evolution;
use Nodes\AbstractNode;

/**
 * Class Simulator
 * @package Evolution
 */
class Simulator{

    private $firstGenerationMembers = [];

    private $fitnessFunction;

    private $mutator;

    private $nodeRegistry;

    public function __construct(NodeRegistry $nodeRegistry, Mutator $mutator){
        $this->nodeRegistry = $nodeRegistry;
        $this->mutator = $mutator;
    }

    public function addFirstGenerationMember($expr){
        $this->firstGenerationMembers[] = $this->nodeRegistry->randomTreeFromExpr($expr);
    }

    public function setFitnessFunction(callable $fitnessFunction){
        $this->fitnessFunction = $fitnessFunction;
    }

    public function simulate($maxSteps, $lookupTable, callable $stopCriteria){
        $previousGeneration = $this->firstGenerationMembers;

        for($x = 0; $x < $maxSteps; $x++){

            /** Mutate each one */
            $changes = [];
            foreach($previousGeneration as &$aSolution){
                $changes[] = $this->mutator->mutate($aSolution);
            }

            /** Rank each one */
            $ranks = [];
            foreach($changes as $aSolution){
                /** @var $aSolution AbstractNode */
                $f = $this->fitnessFunction;
                $rank = $f($aSolution);
                $ranks[] = ["Rank" => $rank, "Draw" => $aSolution->draw(), "Tree" => $aSolution];
            }

            usort($ranks, function($a, $b){
                return $a["Rank"] > $b["Rank"];
            });

            $top50 = [];
            $mutatorSet = [];
            for($i = 0; $i < ceil(count($ranks) / 2); $i++){
                $top50[] = $ranks[$i]["Tree"];
                $mutatorSet[] = $ranks[$i]["Tree"];
            }


            $mutants = [];
            for($i = 0; $i < ceil(count($mutatorSet) / 2); $i++){
                $randomLeft = $mutatorSet[array_rand($mutatorSet, 1)];
                $randomRight = $mutatorSet[array_rand($mutatorSet, 1)];
                $mutants[] = $this->mutator->crossOver($randomLeft, $randomRight)["Child"];
            }

            $previousGeneration = array_merge($mutants, $top50);

            /** Do we need to bail? */
            foreach($previousGeneration as $aPrevious){
                /** @var $aPrevious AbstractNode */
                if ($stopCriteria($aPrevious)){
                    echo "Done with tree: " . $aPrevious->draw() . " = " . $aPrevious->evaluate($lookupTable);
                    die();
                }
            }

            echo "Top fitness for generation $x: " . $ranks[0]["Rank"] . PHP_EOL;
            if ($x === $maxSteps){
                throw new \Exception("We didn't find anything :(");
            }
        }
    }
}