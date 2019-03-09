<?php

namespace RunescapeAPI\Exception;

class UnknownClanException extends \Exception
{
    public function __construct($clan)
    {
        parent::__construct("Unknown Clan: {$clan}");
    }
}