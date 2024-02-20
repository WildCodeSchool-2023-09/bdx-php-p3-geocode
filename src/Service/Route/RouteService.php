<?php

namespace App\Service\Route;

use App\Repository\TerminalRepository;

class RouteService
{
    public function __construct(private TerminalRepository $terminalRepository)
    {
    }
    public function getAllPoints(string $json): array
    {
        return json_decode($json, true);
    }

    public function findClosest(array $start, int $step, array $haystack): array
    {
        $startingPoint = new Point($start);
        $closest = $haystack[0];
        $distance = abs($step - $startingPoint->calcDistanceWith(new Point($closest)));
        for ($i = 1; $i < count($haystack); $i += 1) {
            $newDistance = abs($step - $startingPoint->calcDistanceWith(new Point($haystack[$i])));
            if ($newDistance < $distance) {
                $closest = $haystack[$i];
                $distance = $newDistance;
            }
        }
        return $closest;
    }

    public function findNextStep(
        array $start,
        array $pointList,
        int $stepLength = 100,
        int $marginOfError = 10
    ): array {
        if (count($pointList) <= 3) {
            return $this->findClosest($start, $stepLength, $pointList);
        }
        $tab = $pointList;
        $startingPoint = new Point($start);
        $left = 0;
        $right = count($pointList) - 1;
        $middle = intval(ceil($right) / 2);
        $distMiddle = $startingPoint->calcDistanceWith(new Point($tab[$middle]));
        if ($distMiddle <= $stepLength + $marginOfError && $distMiddle >= $stepLength - $marginOfError) {
            return $pointList[$middle];
        } elseif ($distMiddle < $stepLength - $marginOfError) {
            return $this->findNextStep(
                $start,
                array_slice($pointList, $middle - 1, $right),
                $stepLength,
                $marginOfError
            );
        } else {
            return $this->findNextStep(
                $start,
                array_slice($pointList, $left, $middle + 1),
                $stepLength,
                $marginOfError
            );
        }
    }

    public function findAllSteps(array $pointList, int $stepLength = 100, int $marginOfError = 10): array
    {
        $steps = [];
        $step = $pointList[0];
        $stepPoint = new Point($step);
        $end = new Point($pointList[count($pointList) - 1]);

        while ($stepPoint->calcDistanceWith($end) > $stepLength) {
            $steps[] = $step;
            $step = $this->findNextStep($step, $pointList, $stepLength, $marginOfError);
            $index = array_search($step, $pointList);
            $stepPoint = new Point($step);
            $pointList = array_slice($pointList, $index);
        }
        $steps[] = $step;
        if ($stepPoint->calcDistanceWith(new Point($pointList[count($pointList) - 1])) > 50) {
            $steps[] = $pointList[count($pointList) - 1];
        }
        return $steps;
    }

    public function findTerminals(array $steps): array
    {
        $terminals = [];
        foreach ($steps as $step) {
            foreach ($this->terminalRepository->findNearPosition($step['lng'], $step['lat']) as $terminal) {
                $terminals[] = $terminal;
            }
        }
        return $terminals;
    }
}
