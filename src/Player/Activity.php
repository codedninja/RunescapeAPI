<?php

namespace Tehcodedninja\RunescapeAPI\Player;

use Carbon\Carbon;

class Activity {

    protected $date;

    protected $details;

    protected $text;

    public function __construct($date, $details, $text) {
        $this->date = Carbon::parse($date);
        $this->details = trim($details);
        $this->text = trim($text);
    }

    public function getDate() {
        return $this->date;
    }

    public function getDetails() {
        return $this->details;
    }

    public function getText() {
        return $this->text;
    }
}