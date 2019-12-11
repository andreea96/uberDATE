<?php

namespace App\Service;

use GuzzleHttp\Client;

/**
 * Class PoiService
 */
class PoiService
{
    const API_URL = "https://api.tomtom.com/";
    const API_KEY = "0pPKdwVmTMWG4ZV4qAqIENj5qnbFqhFD";

    /**
     * @param $matchingUser
     * @param $longCurrent
     * @param $latCurrent
     * @return \StdClass
     */
    public function getDateLocation($matchingUser, $latCurrent, $longCurrent) {
        $guzzleClient = new Client([
            'base_uri' => self::API_URL
        ]);

        $latA = ($matchingUser->lat + $latCurrent)/2;
        $longA = ($matchingUser->long + $longCurrent)/2;
        $options = [
            'query' => [
                'key' => self::API_KEY,
                'limit' => 1,
                'lat' => $latA,
                'lon' => $longA,
                'radius' => 3000,
            ]
        ];
        try{
            $response = $guzzleClient->request("GET", "search/2/poiSearch/".$this->getRandPlace().".json", $options);
        }catch (\Throwable $ex) {
            var_dump($ex->getMessage());die;
        }

        $responseObject = json_decode($response->getBody()->getContents());
        $firstResult = $responseObject->results[0];
        $dateLocation = new \StdClass();
        $dateLocation->name = $firstResult->poi->name;
        $dateLocation->lat = $firstResult->position->lat;
        $dateLocation->long = $firstResult->position->lon;

        return $dateLocation;
    }

    /**
     * @return mixed
     */
    private function getRandPlace() {
        $validOptions = ['pub', 'cafe', 'park', 'restaurant', 'cinema'];

        return $validOptions[rand(0, count($validOptions)-1)];
    }


}
