<?php

namespace RunescapeAPI\Player\Skills;

class Constitution extends Skill {
    
    protected $id = 4;

    protected $name = 'constitution';

    protected $maximum_experience = 200000000;

    protected $maximum_level = 99;

    protected $combat = true;

    protected $member = false;
}