<!DOCTYPE html>
<html>
<head>
    <title>Test Page</title>
    <script type="text/javascript" src="/assets/jquery-3.6.0.min.js"></script>
</head>
<body>
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

    <div id="output">--</div>
    <button id="version">Version</button>
    <div id="die1">--</div>
    <button id="roll">Roll Die</button>

    <script>
        $(document).ready(function() {
            const output = $("#output");
            const die1 = $("#die1");

            $("#version").click(function() {
                $.ajax({
                    type: "GET",
                    url: "/api.php",
                    dataType: "json",
                    success: function(data) {
                        output.html("Version: " + data.version);
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + status + " " + error);
                    }
                });
            });

            $("#roll").click(function() {
                $.ajax({
                    type: "GET",
                    url: "/api.php?action=roll",
                    dataType: "json",
                    success: function(data) {
                        die1.html("Rolled: " + data.value);
                    },
                    error: function(xhr, status, error) {
                        console.error("An error occurred: " + status + " " + error);
                    }
                });
            });
        });
    </script>
</body>
</html>
