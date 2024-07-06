<?php
require_once('_config.php');

use Yatzy\Dice;
use Yatzy\YatzyGame;
use Yatzy\YatzyEngine;

$game = new YatzyGame();
$engine = new YatzyEngine();

$game->rollDice();
$score = $engine->scoreTurn($game, 'ones');
$game->addTurn('ones', $score);
echo "Score for 'ones': $score<br>";

$game->rollDice();
$score = $engine->scoreTurn($game, 'twos');
$game->addTurn('twos', $score);
echo "Score for 'twos': $score<br>";

$engine->updateOverallScore($game);
echo "Total Score: " . $game->getScore() . "<br>";
echo "Bonus: " . $game->getBonus() . "<br>";
