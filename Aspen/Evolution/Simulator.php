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
                $ranks[] = ["Rank" => $rank, "Draw" => $aSolution->expression(), "Tree" => $aSolution];
            }

            usort($ranks, function($a, $b){
                return $a["Rank"] > $b["Rank"];
            });


            $totalRank = 0;
            for($i = 0; $i < count($ranks); $i++){
                $totalRank += $ranks[$i]["Rank"];
            }

            /** Roulette selection */
            $pick = function() use ($totalRank, $ranks){
                $rand = rand() * $totalRank;
                $counter = $totalRank;
                for($i = 0; $i < count($ranks); $i++){
                    $counter -= $ranks[$i]["Rank"];
                    if ($counter < $rand){
                        return $ranks[$i]["Tree"];
                    }
                }
                return $ranks[count($ranks) - 1]["Tree"];
            };


            $mutants = [];
            for($j = 0; $j < count($ranks); $j++){
                $randomLeft = $pick();
                $randomRight = $pick();
                $mutants[] = $this->mutator->crossOver($randomLeft, $randomRight)["Child"];
            }

            $previousGeneration = array_merge($mutants, [$ranks[0]["Tree"]]);

            /** Do we need to bail? */
            foreach($previousGeneration as $aPrevious){
                /** @var $aPrevious AbstractNode */
                if ($stopCriteria($aPrevious)){

                    $f = $this->fitnessFunction;
                    $rank = $f($aPrevious);

                    echo "\n\nDone with tree: " . $aPrevious->expression() . " with fitness " . $rank;
                    file_put_contents("tree.txt", $aPrevious->expression());
                    die();
                }
            }

            if (0 === $x % 1){
                echo "Top fitness for generation $x: " . $ranks[0]["Rank"]. " with " .
                    $ranks[0]["Draw"] . PHP_EOL;
            }

            if ($x === $maxSteps - 1){
                echo "We didn't find anything but " . $ranks[0]["Draw"];
            }
        }
    }
}