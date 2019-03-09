<?php

namespace RunescapeAPI\Player\Skills;

class Strength extends Skill {
    
    protected $id = 3;

    protected $name = 'strength';

    protected $maximum_experience = 200000000;

    protected $maximum_level = 99;

    protected $combat = true;

    protected $member = false;
}