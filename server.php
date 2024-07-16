<?php
session_start();

if (!isset($_SESSION['leaderboard'])) {
    $_SESSION['leaderboard'] = [];
}

if (!isset($_SESSION['dice'])) {
    $_SESSION['dice'] = [1, 1, 1, 1, 1];
}

if (!isset($_SESSION['keepDice'])) {
    $_SESSION['keepDice'] = [false, false, false, false, false];
}

if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

if (!isset($_SESSION['rollCount'])) {
    $_SESSION['rollCount'] = 0;
}

if (!isset($_SESSION['scores'])) {
    $_SESSION['scores'] = [
        'Ones' => null,
        'Twos' => null,
        'Threes' => null,
        'Fours' => null,
        'Fives' => null,
        'Sixes' => null,
        'ThreeOfAKind' => null,
        'FourOfAKind' => null,
        'FullHouse' => null,
        'SmallStraight' => null,
        'LargeStraight' => null,
        'Yahtzee' => null,
        'Chance' => null
    ];
}

function getGameState() {
    $gameState = [
        'dice' => $_SESSION['dice'],
        'keepDice' => $_SESSION['keepDice'],
        'score' => $_SESSION['score'],
        'leaderboard' => $_SESSION['leaderboard'],
        'rollCount' => $_SESSION['rollCount'],
        'scores' => $_SESSION['scores']
    ];
    return json_encode($gameState);
}

function rollDice() {
    $keepDice = isset($_POST['keepDice']) ? json_decode($_POST['keepDice']) : [false, false, false, false, false];
    $_SESSION['keepDice'] = $keepDice;
    if ($_SESSION['rollCount'] < 3) {
        $_SESSION['dice'] = array_map(function($die, $keep) {
            return $keep ? $die : rand(1, 6);
        }, $_SESSION['dice'], $keepDice);
        $_SESSION['rollCount']++;
    }
}

function submitScore($name, $score) {
    $newEntry = ['name' => $name, 'score' => $score];
    $_SESSION['leaderboard'][] = $newEntry;

    usort($_SESSION['leaderboard'], function($a, $b) {
        return $b['score'] - $a['score'];
    });

    $_SESSION['leaderboard'] = array_slice($_SESSION['leaderboard'], 0, 10);
}

function scoreCategory($category) {
    $dice = $_SESSION['dice'];
    $score = 0;
    $counts = array_count_values($dice);

    switch ($category) {
        case 'Ones':
            $score = array_sum(array_map(fn($d) => $d == 1 ? 1 : 0, $dice));
            break;
        case 'Twos':
            $score = array_sum(array_map(fn($d) => $d == 2 ? 2 : 0, $dice));
            break;
        case 'Threes':
            $score = array_sum(array_map(fn($d) => $d == 3 ? 3 : 0, $dice));
            break;
        case 'Fours':
            $score = array_sum(array_map(fn($d) => $d == 4 ? 4 : 0, $dice));
            break;
        case 'Fives':
            $score = array_sum(array_map(fn($d) => $d == 5 ? 5 : 0, $dice));
            break;
        case 'Sixes':
            $score = array_sum(array_map(fn($d) => $d == 6 ? 6 : 0, $dice));
            break;
        case 'ThreeOfAKind':
            if (max($counts) >= 3) {
                $score = array_sum($dice);
            }
            break;
        case 'FourOfAKind':
            if (max($counts) >= 4) {
                $score = array_sum($dice);
            }
            break;
        case 'FullHouse':
            if (in_array(3, $counts) && in_array(2, $counts)) {
                $score = 25;
            }
            break;
        case 'SmallStraight':
            if ((count(array_intersect([1, 2, 3, 4], $dice)) == 4) ||
                (count(array_intersect([2, 3, 4, 5], $dice)) == 4) ||
                (count(array_intersect([3, 4, 5, 6], $dice)) == 4)) {
                $score = 30;
            }
            break;
        case 'LargeStraight':
            if ((count(array_intersect([1, 2, 3, 4, 5], $dice)) == 5) ||
                (count(array_intersect([2, 3, 4, 5, 6], $dice)) == 5)) {
                $score = 40;
            }
            break;
        case 'Yahtzee':
            if (max($counts) == 5) {
                $score = 50;
            }
            break;
        case 'Chance':
            $score = array_sum($dice);
            break;
    }

    $_SESSION['scores'][$category] = $score;
    $_SESSION['score'] += $score;
    $_SESSION['rollCount'] = 0;
    $_SESSION['keepDice'] = [false, false, false, false, false];

    
    $allScored = array_reduce($_SESSION['scores'], fn($carry, $score) => $carry && $score !== null, true);
    if ($allScored) {
        $_SESSION['rollCount'] = 3; 
    }
}

function startOver() {
    $_SESSION['dice'] = [1, 1, 1, 1, 1];
    $_SESSION['keepDice'] = [false, false, false, false, false];
    $_SESSION['score'] = 0;
    $_SESSION['rollCount'] = 0;
    $_SESSION['scores'] = [
        'Ones' => null,
        'Twos' => null,
        'Threes' => null,
        'Fours' => null,
        'Fives' => null,
        'Sixes' => null,
        'ThreeOfAKind' => null,
        'FourOfAKind' => null,
        'FullHouse' => null,
        'SmallStraight' => null,
        'LargeStraight' => null,
        'Yahtzee' => null,
        'Chance' => null
    ];
}

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'get_game_state':
        echo getGameState();
        break;
    case 'roll_dice':
        rollDice();
        echo getGameState();
        break;
    case 'submit_score':
        $name = $_POST['name'] ?? 'Anonymous';
        $score = $_SESSION['score'] ?? 0;
        submitScore($name, $score);
        echo getGameState();
        break;
    case 'score_category':
        $category = $_POST['category'] ?? '';
        scoreCategory($category);
        echo getGameState();
        break;
    case 'start_over':
        startOver();
        echo getGameState();
        break;
}
?>
