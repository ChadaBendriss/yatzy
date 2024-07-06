<?php

namespace Yatzy;

class YatzyGame {
    private $roll;
    private $dice;
    private $keep;
    private $turns;
    private $score;
    private $bonus;

    public function __construct() {
        $this->roll = 0;
        $this->dice = [0, 0, 0, 0, 0];
        $this->keep = [false, false, false, false, false];
        $this->turns = [];
        $this->score = 0;
        $this->bonus = 0;
    }

    public function getRoll() {
        return $this->roll;
    }

    public function getDice() {
        return $this->dice;
    }

    public function getKeep() {
        return $this->keep;
    }

    public function getTurns() {
        return $this->turns;
    }

    public function getScore() {
        return $this->score;
    }

    public function getBonus() {
        return $this->bonus;
    }

    public function setRoll($roll) {
        $this->roll = $roll;
    }

    public function setDice($index, $value) {
        if ($index >= 0 && $index < 5) {
            $this->dice[$index] = $value;
        }
    }

    public function setKeep($index, $value) {
        if ($index >= 0 && $index < 5) {
            $this->keep[$index] = $value;
        }
    }

    public function rollDice() {
        for ($i = 0; $i < 5; $i++) {
            if (!$this->keep[$i]) {
                $this->dice[$i] = rand(1, 6);
            }
        }
        $this->roll++;
    }

    public function addTurn($scoreBox, $score) {
        $this->turns[] = ['scoreBox' => $scoreBox, 'score' => $score];
    }

    public function setScore($score) {
        $this->score = $score;
    }

    public function setBonus($bonus) {
        $this->bonus = $bonus;
    }
}
