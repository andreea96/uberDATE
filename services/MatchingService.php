<?php

namespace App\Service;

use GuzzleHttp\Client;

/**
 * Class MatchingService
 */
class MatchingService
{
    const MOCKDATALINK = "http://mockbcknd.tk/";

    /**
     * @param $personality
     * @param $gender
     * @return array
     */
    public function getMatchingUsers($personality, $gender)
    {
        $guzzleClient = new Client([
            'base_uri' => self::MOCKDATALINK,
        ]);
        $personality = strtoupper($personality);
        $guzzleResponse = $guzzleClient->get('/');
        $users = json_decode($guzzleResponse->getBody()->getContents());
        $users = $this->completeUsers($users);
        file_put_contents("../db/nearby_users.json", json_encode($users));
        $compatibilities = json_decode(file_get_contents("../db/compatibilities.json"));
        $scoresByPersonality = $compatibilities->$personality;

        $matches = [];
        foreach ($users as $username => $user){
            $userPersonality = $user->personalityType;
            $userGender = $user->gender;
            if($scoresByPersonality->$userPersonality >= 3 && $userGender === $gender)
            {
                $matches[$username] = $user;
                $matches[$username]->score = $scoresByPersonality->$userPersonality;
            }
        }

        return $matches;
    }

    /**
     * the loged user will have his personality type, but for the demo we ll mock him
     */
    private function getRandomPersonalityType()
    {
        $compatibilities = json_decode(file_get_contents("../db/compatibilities.json"));
        $personalityTypes = array_keys((array)$compatibilities);

       return $personalityTypes[rand(0,count($personalityTypes)-1)];
    }

    /**
     * @return mixed
     */
    private function getRandomGender()
    {
        $genders = ['m', 'f', 'o'];

        return $genders[rand(0,2)];
    }

    private function completeUsers($users)
    {
        $appUsers = [];
        foreach ($users->features as $user)
        {
            $key = strtolower($user->properties->first_name).$user->properties->last_name;
            $appUsers[$key] = new \StdClass();
            $appUsers[$key]->firstName = $user->properties->first_name;
            $appUsers[$key]->lastName = $user->properties->last_name;
            $appUsers[$key]->lat = $user->geometry->coordinates[1];
            $appUsers[$key]->long = $user->geometry->coordinates[0];
            $appUsers[$key]->personalityType = $this->getRandomPersonalityType();
            $appUsers[$key]->gender = $this->getRandomGender();
        }

        return $appUsers;
    }
}
