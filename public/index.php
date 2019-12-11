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
    $body = $response->getBody();
    $body->write(json_encode($matches));
    $newResponse = $response->withHeader('Content-type', 'application/json');

    return  $newResponse;
});

$app->get('/poi/{matchedUser}/{latCurrent}/{longCurrent}', function (Request $request, Response $response, $args){
    $poiService = new PoiService();

    $users = json_decode(file_get_contents("../db/nearby_users.json"));

    $currentLat = $args['latCurrent'];
    $currentLong = $args['longCurrent'];
    $matchedUser = $args['matchedUser'];
    $dateLocation = $poiService->getDateLocation($users->$matchedUser, $currentLat, $currentLong);

    return $response->getBody()->write(json_encode($dateLocation));
});

$app->get('/test', function (Request $request, Response $response) {

});
$app->run();