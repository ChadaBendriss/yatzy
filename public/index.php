<?php
require_once('_config.php');

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    // Calculate initial scores and bonus information
    $game = new Yatzy\YatzyGame();
    $engine = new Yatzy\YatzyEngine();

    // Roll dice and calculate scores
    $game->rollDice();
    $scoreOnes = $engine->scoreTurn($game, 'ones');
    $game->addTurn('ones', $scoreOnes);

    $game->rollDice();
    $scoreTwos = $engine->scoreTurn($game, 'twos');
    $game->addTurn('twos', $scoreTwos);

    $engine->updateOverallScore($game);
    $totalScore = $game->getScore();
    $bonus = $game->getBonus();

    // Generate the view
    $view = file_get_contents("{$GLOBALS["appDir"]}/views/index.html");
    $view = str_replace('Score for \'ones\': 0', 'Score for \'ones\': ' . $scoreOnes, $view);
    $view = str_replace('Score for \'twos\': 0', 'Score for \'twos\': ' . $scoreTwos, $view);
    $view = str_replace('Total Score: 0', 'Total Score: ' . $totalScore, $view);
    $view = str_replace('Bonus: 0', 'Bonus: ' . $bonus, $view);

    // Return the response
    $response->getBody()->write($view);
    return $response;
});

$app->run();
