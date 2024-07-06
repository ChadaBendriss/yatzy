<?php
require_once('_config.php');

use Yatzy\Dice;
use Yatzy\YatzyGame;

$game = new YatzyGame();
echo "Initial game state:<br>";
echo "Roll: " . $game->getRoll() . "<br>";
echo "Dice: " . implode(", ", $game->getDice()) . "<br>";
echo "Keep: " . implode(", ", $game->getKeep()) . "<br><br>";

$game->rollDice();
echo "After rolling the dice:<br>";
echo "Roll: " . $game->getRoll() . "<br>";
echo "Dice: " . implode(", ", $game->getDice()) . "<br>";
echo "Keep: " . implode(", ", $game->getKeep()) . "<br><br>";

$game->setKeep(0, true);
$game->rollDice();
echo "After rolling the dice again, keeping the first dice:<br>";
echo "Roll: " . $game->getRoll() . "<br>";
echo "Dice: " . implode(", ", $game->getDice()) . "<br>";
echo "Keep: " . implode(", ", $game->getKeep()) . "<br>";
