<?php

namespace RunescapeAPI\Player\Skills;

class Prayer extends Skill {
    
    protected $id = 6;

    protected $name = 'prayer';

    protected $maximum_experience = 200000000;

    protected $maximum_level = 99;

    protected $combat = false;

    protected $member = false;
}