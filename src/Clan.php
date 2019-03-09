<?php

namespace RunescapeAPI;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\Exception\RequestException;
use Symfony\Component\DomCrawler\Crawler;
use RunescapeAPI\Exception\UnknownClanException;
use RunescapeAPI\Player\PlayerCollection;

class Clan {
    
    // Clan name
    protected $name = '';

    // Clan Runescape ID
    protected $id;

    // Members count
    protected $total_members = 0;

    // Total Level (Average)
    protected $total_level = 0;

    // Kill / Death Ratio
    protected $kdr = 0.0;

    // Total XP
    protected $total_xp = 0;

    // Citdal Level
    protected $citdal_level = 1;

    // Cache Members
    protected $members;

    // Guzzle Client
    protected $guzzle;

    // Endpoints
    protected $endpoints = [
        'info'      => 'http://services.runescape.com/m=clan-home/a=26/clan/%s',
        'stats'     => 'http://services.runescape.com/m=clan-hiscores/a=26/compare.ws?clanName=%s',
        'members'   => 'http://services.runescape.com/m=clan-hiscores/a=26/members.ws?pageSize=45&clanName=%s&pageNum=%s'
    ];

    // Cache returned data
    protected $returned_data = [

    ];

    public function __construct($clan_name) {
        $this->guzzle = new Guzzle();
        $this->name = $clan_name;

        // Get info on clan
        $info_response = $this->guzzle->request('GET', sprintf($this->endpoints['info'], $this->name));
        
        $info_crawler = new Crawler((string) $info_response->getBody());

        $this->name = $info_crawler->filter('span.G0')->first()->text();

        // Check if clan exist
        if($this->name == 'Error') {
            throw new UnknownClanException($clan_name);
        }

        $this->total_members = $info_crawler->filter('a#Clanstat_1 > span.clanstatVal')->text();

        $this->total_level = $info_crawler->filter('a#Clanstat_2 > span.clanstatVal')->text();

        $this->kdr = $info_crawler->filter('a#Clanstat_3 > span.clanstatVal')->text();

        $this->total_xp = $info_crawler->filter('a#Clanstat_4 > span.clanstatVal')->text();

        $this->citdal_level = $info_crawler->filter('a#Clanstat_5 > span.clanstatVal')->text();

        $this->members = new PlayerCollection;
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        if($this->id != null) {
            return $this->id;
        }

        $this->getMembers();

        return $this->id;
    }

    public function getMembers() {
        if (count($this->members) > 0) {
            return $this->members;
        }

        try {
            // Get amount of pages and loop through to get all members
            $pages = ceil($this->total_members / 45);

            for ($page=0; $page < $pages; $page++) { 
                $members_response = $this->guzzle->request('GET', sprintf($this->endpoints['members'], $this->name, $page+1));

                $members_crawler = new Crawler((string) $members_response->getBody());

                $members = $members_crawler->filter('.membersListRow');

                $members_collection = PlayerCollection::clanFactory($members);

                $this->members = $this->members->merge($members_collection);

                $this->id = $members_crawler->filter("input[name='clanId']")->attr('value');
            }

        } catch (RequestException $e) {
            
        }

        return $this->members;
    }
}