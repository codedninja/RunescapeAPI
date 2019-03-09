<?php

namespace RunescapeAPI\Player\Skills;

class Crafting extends Skill {
    
    protected $id = 13;

    protected $name = 'crafting';

    protected $maximum_experience = 200000000;

    protected $maximum_level = 99;

    protected $combat = false;

    protected $member = false;
}