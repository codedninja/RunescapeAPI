<?php

namespace RunescapeAPI;

use GuzzleHttp\Client as Guzzle;
use \GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\RequestException;
use RunescapeAPI\Exception\UnknownPlayerException;
use RunescapeAPI\Player\Skills\SkillsCollection;
use RunescapeAPI\Player\ActivitiesCollection;

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

    // Endpoints
    protected $endpoints = [
        'profile'   => 'https://apps.runescape.com/runemetrics/profile/profile?user=%s&activities=20',
        'player_details'      => 'https://secure.runescape.com/m=website-data/playerDetails.ws'
    ];

    // Cache of profile data
    protected $profile;

    protected $guzzle;

    // Clan stats
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
        return $this->clan_rank;
    }

    public function getMember() {
        return $this->member;
    }

    public function getClanTotalExperience() {
        return $this->clan_total_xp;
    }

    public function getClanKills() {
        return $this->kills;
    }

    public function getOnline() {
        return $this->online;
    }

    public function getClan() {
        $raw_clan = $this->guzzle->request('GET', $this->endpoints['player_details'], [
            'query' => [
                'names' => '["'.$this->name.'"]',
                'callback' => 'angular.callbacks._1'
            ],
            'on_stats' => function ($stats) use (&$url) {
                $url = $stats->getEffectiveUri();
            }
        ]);

        $clan = $this->jsonp_decode((string)$raw_clan->getBody());
        
        return (isset($clan[0]->clan)) ? $clan[0]->clan : null;
    }

    private function jsonp_decode($jsonp, $assoc = false) { // PHP 5.3 adds depth as third parameter to json_decode
        $jsonp_string = preg_replace("/[^(]*\((.*)\)(?:;|)/", "$1", $jsonp);
        return json_decode($jsonp_string, $assoc);
    }
}
    