<?php

namespace App\Service;

use GuzzleHttp\Client;


class PoiService
{
    const API_URL = "https://api.tomtom.com/";
    const API_KEY = "0pPKdwVmTMWG4ZV4qAqIENj5qnbFqhFD";

    public function getDateLocation($matchingUser, $currentUser) {
        $guzzleClient = new Client([
            'base_uri' => self::API_URL
        ]);

        $latA = ($matchingUser->lat + $currentUser->lat)/2;
        $longA = ($matchingUser->long + $currentUser->long)/2;

        $options = [
            'query' => [
                'key' => self::API_KEY,
                'limit' => 1,
                'lat' => $latA,
                'lon' => $longA,
                'radius' => 500,
            ]
        ];
        try{
            $response = $guzzleClient->request("GET", "search/2/poiSearch/".$this->getRandPlace().".json", $options);
        }catch (\Throwable $ex) {
            var_dump($ex->getMessage());die;

        }
        $responseObject = json_decode($response->getBody()->getContents());
        $dateLocation = new \StdClass();
        $dateLocation->name = $responseObject->results[0]->poi->name;
        $dateLocation->lat = $responseObject->results[0]->position->lat;
        $dateLocation->long = $responseObject->results[0]->position->lon;

       return $dateLocation;

    }

    private function getRandPlace() {
        $validOptions = ['pub', 'cafe', 'park', 'restaurant', 'cinema'];

        return $validOptions[rand(0, count($validOptions)-1)];
    }
}