<?php

namespace App\Service;

/**
 * Class MatchingService
 */
class MatchingService
{
    /**
     * @param $personality
     * @return array
     */
    public function getMatchingUsers($personality)
    {
        $personality = strtoupper($personality);
        $users = json_decode(file_get_contents("../db/nearby_users.json"));
        $compatibilities = json_decode(file_get_contents("../db/compatibilities.json"));

        $scoresByPersonality = $compatibilities->$personality;

        $matches = [];
        foreach ($users as $username => $user){
            $userPersonality = $user->personalityType;
            if($scoresByPersonality->$userPersonality >= 3)
            {
                $matches[$username] = $user;
                $matches[$username]->score = $scoresByPersonality->$userPersonality;
            }
        }

        return $matches;
    }
}
