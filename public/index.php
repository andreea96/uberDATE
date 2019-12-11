<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use \App\Service\MatchingService;
use App\Service\PoiService;

include("../services/MatchingService.php");
include("../services/PoiService.php");
require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

$app->get('/matches/{personality}', function (Request $request, Response $response, $args) {

    $matchingService = new MatchingService();
    $matches = $matchingService->getMatchingUsers($args['personality']);
    $response->getBody()->write(json_encode($matches));

    return $response;
});

$app->get('/poi/{currentUser}/{dateUser}', function (Request $request, Response $response, $args){
    $poiService = new PoiService();
    $users = json_decode(file_get_contents("../db/nearby_users.json"));
    $user1 = $args['currentUser'];
    $user2 = $args['dateUser'];
    $dateLocation = $poiService->getDateLocation($users->$user1, $users->$user2);

    return $response->getBody()->write(json_encode($dateLocation));

});



$app->run();