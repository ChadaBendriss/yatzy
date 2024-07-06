<?php
namespace Yatzy\Test;

use Yatzy\YatzyGame;
use PHPUnit\Framework\TestCase;

class YatzyGameTest extends TestCase
{
    public function testInitialState()
    {
        $game = new YatzyGame();
        $this->assertEquals(0, $game->getRoll());
        $this->assertEquals([0, 0, 0, 0, 0], $game->getDice());
        $this->assertEquals([false, false, false, false, false], $game->getKeep());
        $this->assertEquals([], $game->getTurns());
        $this->assertEquals(0, $game->getScore());
        $this->assertEquals(0, $game->getBonus());
    }
}
