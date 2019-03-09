<?php 

namespace RunescapeAPI\Player;

use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;

class PlayerCollection extends Collection
{

    public static function clanFactory($players)
    {
        $collection = new static;
        
        foreach($players as $player) {
            $player = new Crawler($player);

            $avatar = $player->filter('span.avatar > img')->attr('src');

            $name = $player->filter('span.name')->text();

            $clan_rank = $player->filter('span.clanRank')->text();

            $member = ($player->filter('span.proStatus img')) ? true : false;

            $clan_total_xp = str_replace(',', '', $player->filter('span.totalXP')->text());

            $kills = $player->filter('span.kills')->text();

            $online = (trim($player->filter('span.onlineStatus > span.world')->text()) == 'Offline') ? -1 : trim(str_replace('RS', '', $player->filter('span.onlineStatus > span.world')->text()));

            $collection->push(
                new Player([
                    "name"          => $name,
                    "avatar"        => $avatar,
                    "clan_rank"     => $clan_rank,
                    "member"        => $member,
                    "clan_total_xp" => $clan_total_xp,
                    "kills"         => $kills,
                    "online"        => $online,
                ])
            );
        }

        return $collection;
    }
}