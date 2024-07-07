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
?>

<!-- HTML and JavaScript for button and output -->
<div id="output">--</div>
<button id="version">Version</button>

<script>
const output = document.getElementById("output");
const version = document.getElementById("version");
version.onclick = function(e) {
  const xmlhttp = new XMLHttpRequest();

  xmlhttp.onreadystatechange = function() {
    if (xmlhttp.readyState == XMLHttpRequest.DONE) {
      if (xmlhttp.status == 200) {
        output.innerHTML = xmlhttp.responseText;
      }
    }
  };

  xmlhttp.open("GET", "/api.php", true);
  xmlhttp.send();
}
</script>
