<?php 

namespace RunescapeAPI\Player\Skills;

use Illuminate\Support\Collection;
use Symfony\Component\DomCrawler\Crawler;
use RunescapeAPI\Player\Skills\Repository;

class SkillsCollection extends Collection
{
    public static function factory($raw_skills) {
        $repository = new static;
        $skills     = new Repository();

        foreach($raw_skills as $raw_skill) {
            $skill = $skills->find($raw_skill->id);

            $repository->push(
                new $skill(
                    $raw_skill->level,
                    $raw_skill->xp,
                    $raw_skill->rank
                )
            );
        }

        return $repository;
    }
}