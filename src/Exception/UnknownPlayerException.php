<?php

namespace RunescapeAPI\Exception;

class UnknownPlayerException extends \Exception
{
    public function __construct($player)
    {
        parent::__construct("Unknown Player: {$player}");
    }
}