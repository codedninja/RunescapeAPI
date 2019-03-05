<?php

namespace Tehcodedninja\RunescapeAPI\Player\Skills;

class Attack extends Skill {
    
    protected $id = 1;

    protected $name = 'attack';

    protected $maximum_experience = 200000000;

    protected $maximum_level = 99;

    protected $combat = true;

    protected $member = false;
}