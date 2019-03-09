<?php

namespace RunescapeAPI;

use GuzzleHttp\Client as Guzzle;
use \GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use RunescapeAPI\Exception\UnknownPlayerException;
use RunescapeAPI\Player\Skills\SkillsCollection;
use RunescapeAPI\Player\ActivitiesCollection;
use RunescapeAPI\Clan;

class Player {

    // Player Name
    protected $name = '';

    // Player Avatar
    protected $avatar = '';

    // Quests completed
    protected $quests_completed = 0;

    // Quests not started
    protected $quests_not_started = 0;

    // Quests started
    protected $quests_started = 0;

    // Rank
    protected $rank = 0;

    // Activites
    protected $activities = [];

    // Skills
    protected $skills = [];

    // Monthly Experience Cache
    protected $monthly_experience = [];

    // Endpoints
    protected $endpoints = [
        'profile'   => 'https://apps.runescape.com/runemetrics/profile/profile?user=%s&activities=20',
        'player_details'      => 'https://secure.runescape.com/m=website-data/playerDetails.ws',
        'xp-monthly'    => 'https://apps.runescape.com/runemetrics/xp-monthly?searchName=%s&skillid=%s',
        'quests'    => 'https://apps.runescape.com/runemetrics/quests?user=%s'
    ];

    // Cache of profile data
    protected $profile;

    protected $guzzle;

    // Clan stats
    protected $clan;

    protected $clan_rank = '';

    protected $member = false;

    protected $clan_total_xp = '';

    protected $kills = 0;

    protected $online = -1;
    
    public function __construct($name) {
        if(is_array($name)) {
            $this->name = $name['name'];
            $this->avatar = $name['avatar'];
            $this->clan_rank = $name['clan_rank'];
            $this->member = $name['member'];
            $this->clan_total_xp = $name['clan_total_xp'];
            $this->kills = $name['kills'];
            $this->online = $name['online'];

            return $this;
        }

        $this->guzzle = new Guzzle();
        $this->skills = new SkillsCollection;

        $this->name = $name;
        $this->_getProfile();
    }

    private function _getProfile() {
        if($this->profile != null) {
            return;
        }

        try {
            $profile_response = $this->guzzle->request('GET', sprintf($this->endpoints['profile'], $this->name));

            $this->profile = json_decode($profile_response->getBody());

            if(isset($this->profile->error)) {
                if($this->profile->error == "PROFILE_PRIVATE") {
                    throw new PrivatePlayerException($this->name);
                }

                throw new UnknownPlayerException($this->name);
            }

            $this->name = $this->profile->name;
            $this->rank = str_replace(',','',$this->profile->rank);
            $this->quests_completed = $this->profile->questscomplete;
            $this->quests_not_started = $this->profile->questsnotstarted;
            $this->quests_started = $this->profile->questsstarted;

            $this->avatar = sprintf("http://services.runescape.com/m=avatar-rs/a=26/%s/chat.png", $this->name);

            $this->skills = SkillsCollection::factory($this->profile->skillvalues);
            $this->activities = ActivitiesCollection::factory($this->profile->activities);

        } catch (RequestException $e) {
            throw new UnknownPlayerException($this->name);
        }    
    }

    public function getName() {
        return $this->name;
    }

    public function getAvatar() {
        return $this->avatar;
    }

    public function getQuestsCompleted() {
        $this->_getProfile();
        return $this->quests_completed;
    }

    public function getQuestsNotStarted() {
        $this->_getProfile();
        return $this->quests_not_started;
    }

    public function getQuestsStarted() {
        $this->_getProfile();
        return $this->quests_started;
    }

    public function getRank() {
        $this->_getProfile();
        return $this->rank;
    }

    public function getActivities() {
        $this->_getProfile();
        return $this->activities;
    }

    public function getSkills() {
        $this->_getProfile();
        return $this->skills;
    }

    public function getClanRank() {
        $this->getClan();
        return $this->clan_rank;
    }

    public function getMember() {
        $this->getClan();
        return $this->member;
    }

    public function getClanTotalExperience() {
        $this->getClan();
        return $this->clan_total_xp;
    }

    public function getClanKills() {
        $this->getClan();
        return $this->kills;
    }

    public function getOnline() {
        $this->getClan();
        return $this->online;
    }

    public function getClan() {
        if($this->clan != null) {
            return $this->clan;
        }

        // Get Clan name from player details
        $profile_details_response = $this->guzzle->request('GET', $this->endpoints['player_details'], [
            'query' => [
                'names' => '["'.$this->name.'"]',
                'callback' => 'angular.callbacks._1'
            ]
        ]);

        $profile_details = $this->jsonp_decode((string)$profile_details_response->getBody());

        if(!isset($profile_details[0]->clan)) {
            throw new PlayerNotInAClan($this->name);
        }

        // Get Clan
        $this->clan = new Clan($profile_details[0]->clan);
        
        // Parse out clan details
        $clan_members = $this->clan->getMembers();

        $player = $clan_members->findByName($this->name);

        $this->clan_rank = $player->getClanRank();
        $this->member = $player->getMember();
        $this->clan_total_xp = $player->getClanTotalExperience();
        $this->kills = $player->getClanKills();
        $this->online = $player->getOnline();

        return $this->clan;
    }

    public function getMonthlyExperience($skill = -1) {
        if(isset($this->monthly_experience[$skill])) {
            return $this->monthly_experience[$skill];
        }
        
        $monthly_response = $this->guzzle->request('GET', sprintf($this->endpoints['xp-monthly'], $this->name, $skill));

        $monthly_experience = json_decode((string)$monthly_response->getBody());

        $this->monthly_experience[$skill] = $monthly_experience->monthlyXpGain[0];
        
        return $this->monthly_experience[$skill];
    }

    private function jsonp_decode($jsonp, $assoc = false) {
        $jsonp_string = preg_replace("/[^(]*\((.*)\)(?:;|)/", "$1", $jsonp);
        return json_decode($jsonp_string, $assoc);
    }
}
    