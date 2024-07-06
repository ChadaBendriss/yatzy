<?php

namespace Yatzy;

class YatzyEngine {
    public function scoreTurn($game, $scoreBox) {
        $dice = $game->getDice();
        switch ($scoreBox) {
            case 'ones':
                return array_sum(array_filter($dice, function ($die) {
                    return $die == 1;
                }));
            case 'twos':
                return array_sum(array_filter($dice, function ($die) {
                    return $die == 2;
                })) * 2;
            case 'threes':
                return array_sum(array_filter($dice, function ($die) {
                    return $die == 3;
                })) * 3;
            // Add cases for other score boxes as needed
            default:
                return 0;
        }
    }

    public function updateOverallScore($game) {
        $totalScore = 0;
        // Assuming the game has a method to get all turns and score boxes
        $turns = $game->getTurns();
        foreach ($turns as $turn) {
            $totalScore += $turn['score'];
        }
        $game->setScore($totalScore);
        
        // Calculate bonus if applicable
        if ($totalScore >= 63) {
            $game->setBonus(50);
        } else {
            $game->setBonus(0);
        }
    }
}
