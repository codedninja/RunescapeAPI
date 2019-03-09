<?php

namespace RunescapeAPI\Exception;

class PlayerNotInAClanException extends \Exception
{
    public function __construct($player)
    {
        parent::__construct("Player is not in a clan: {$player}");
    }
}