<?php
namespace Yatzy\Test;

use Yatzy\YatzyGame;
use Yatzy\YatzyEngine;
use PHPUnit\Framework\TestCase;

class YatzyEngineTest extends TestCase
{
    public function testScoreTurn()
    {
        $game = new YatzyGame();
        $engine = new YatzyEngine();

        // Set the dice values to a known state
        $game->setDice(0, 1);
        $game->setDice(1, 1);
        $game->setDice(2, 1);
        $game->setDice(3, 2);
        $game->setDice(4, 2);

        // Add debugging output
        echo "Dice: " . implode(", ", $game->getDice()) . "\n";

        $score = $engine->scoreTurn($game, 'ones');
        echo "Score for 'ones': " . $score . "\n";
        $this->assertEquals(3, $score);

        $score = $engine->scoreTurn($game, 'twos');
        echo "Score for 'twos': " . $score . "\n";
        $this->assertEquals(4, $score);
    }

    public function testUpdateOverallScore()
    {
        $game = new YatzyGame();
        $engine = new YatzyEngine();

        $game->addTurn('ones', 3);
        $game->addTurn('twos', 4);

        $engine->updateOverallScore($game);
        $this->assertEquals(7, $game->getScore());
        $this->assertEquals(0, $game->getBonus());

        $game->addTurn('sixes', 60);
        $engine->updateOverallScore($game);
        $this->assertEquals(67, $game->getScore());
        $this->assertEquals(50, $game->getBonus());
    }
}
