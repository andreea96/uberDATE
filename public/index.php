<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Service\MatchingService;
include("../services/MatchingService.php");
require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

$app->get('/matches/{personality}', function (Request $request, Response $response, $args) {

    $matchingService = new MatchingService();
    $matches = $matchingService->getMatchingUsers($args['personality']);
    $response->getBody()->write(json_encode($matches));

    return $response;
});



$app->run();