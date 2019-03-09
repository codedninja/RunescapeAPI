<?php 

namespace RunescapeAPI\Player;

use Illuminate\Support\Collection;

class ActivitiesCollection extends Collection
{

    public static function factory($activities)
    {
        $collection = new static;
        
        foreach($activities as $activity) {
            $collection->push(
                new Activity(
                    $activity->date,
                    $activity->details,
                    $activity->text
                )
            );
        }

        return $collection;
    }
}