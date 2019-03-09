<?php

namespace RunescapeAPI\Exception;

class PrivatePlayerException extends \Exception
{
    public function __construct($player)
    {
        parent::__construct("Player has a private profile: {$player}");
    }
}