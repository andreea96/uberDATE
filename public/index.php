<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use GuzzleHttp\Psr7\GuzzleRequest;

require __DIR__ . '/../vendor/autoload.php';

$app = new \Slim\App();

$app->get('/', function (Request $request, Response $response, $args) {
    $guzzleClient = new \GuzzleHttp\Client([
        'base_uri' => 'http://mockbcknd.tk/'
    ]);
    $tomtomResponse = $guzzleClient->request('GET', '/');
    $personalities = file_get_contents("./../db/personality_matching.json");
    var_dump(json_decode($personalities));die;
    $response->getBody()->write($tomtomResponse);

    return $response;
});

$app->run();