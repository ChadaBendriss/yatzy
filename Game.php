<?php
session_start();

class Game {
    public static function rollDice() {
        $_SESSION['dice'] = array_map(function() {
            return rand(1, 6);
        }, range(1, 5));
        return $_SESSION['dice'];
    }

    public static function getGameState() {
        return [
            'dice' => $_SESSION['dice'] ?? [1, 1, 1, 1, 1],
            'score' => $_SESSION['score'] ?? 0,
            'leaderboard' => $_SESSION['leaderboard'] ?? []
        ];
    }

    public static function submitScore($name) {
        $score = $_SESSION['score'] ?? 0;
        $newEntry = ['name' => $name, 'score' => $score];
        $_SESSION['leaderboard'][] = $newEntry;

        // Sort the leaderboard by score in descending order
        usort($_SESSION['leaderboard'], function($a, $b) {
            return $b['score'] - $a['score'];
        });

        // Keep only the top 10 scores
        $_SESSION['leaderboard'] = array_slice($_SESSION['leaderboard'], 0, 10);
    }
}
?>
