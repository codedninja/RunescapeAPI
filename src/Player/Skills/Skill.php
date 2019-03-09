<?php

namespace RunescapeAPI\Player\Skills;

abstract class Skill {

    // Skill ID
    protected $id;

    // Skill Name
    protected $name;

    // Maximum Experience
    protected $maximum_experience;

    // Maximum Level
    protected $maximum_level;

    // Combat skill
    protected $combat;

    // Rank
    protected $rank;

    // Member Skill
    protected $member;

    public function __construct($level = 1, $experience = 0, $rank = 0) {
        $this->level = $level;
        $this->experience = $experience;
        $this->rank = $rank;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getMaximumExperience() {
        return $this->maximum_experience;
    }

    public function getMaximumLevel() {
        return $this->maximum_level;
    }

    public function isCombat() {
        return $this->combat;
    }

    public function isMember() {
        return $this->member;
    }
}