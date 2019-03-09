<?php

namespace RunescapeAPI\Player\Skills;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Repository extends Collection
{
    public function __construct()
    {
        parent::__construct([
            new Overall(),
            new Attack(),
            new Defence(),
            new Strength(),
            new Constitution(),
            new Ranged(),
            new Prayer(),
            new Magic(),
            new Cooking(),
            new Woodcutting(),
            new Fletching(),
            new Fishing(),
            new Firemaking(),
            new Crafting(),
            new Smithing(),
            new Mining(),
            new Herblore(),
            new Agility(),
            new Thieving(),
            new Slayer(),
            new Farming(),
            new Runecrafting(),
            new Hunter(),
            new Construction(),
            new Summoning(),
            new Dungeoneering(),
            new Divination(),
            new Invention(),
        ]);
    }
    
    public function find($id)
    {
        return Arr::first($this->items, function ($key, $skill) use ($id) {
            return $key->getId() === $id;
        }, null);
    }
    
    public function findByName($name)
    {
        return Arr::first($this->items, function ($key, $skill) use ($name) {
            return $skill->getName() === strtolower($name);
        }, null);
    }
}