<?php

namespace RunescapeAPI\Player\Skills;

class Ranged extends Skill {
    
    protected $id = 5;

    protected $name = 'ranged';

    protected $maximum_experience = 200000000;

    protected $maximum_level = 99;

    protected $combat = true;

    protected $member = false;
}