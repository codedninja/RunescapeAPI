<?php

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase {

    public function testPlayerInstance(): void
    {
        $player = new Tehcodedninja\RunescapeAPI\Player('Codedninja');

        $this->assertInstanceOf(Tehcodedninja\RunescapeAPI\Player::class, new Tehcodedninja\RunescapeAPI\Player('codedninja'));
    }

    public function testPlayerExist(): void
    {
        $player = new Tehcodedninja\RunescapeAPI\Player('codedninja');

        $this->assertEquals('codedninja', $player->getName());
    }

    public function testPlayerDoesntExist(): void
    {
        $this->expectException(Tehcodedninja\RunescapeAPI\Exception\UnknownPlayerException::class);
        
        $player = new Tehcodedninja\RunescapeAPI\Player('PlayerDoesntExist');
    }

    public function testPlayerHasClan(): void
    {
        $player = new Tehcodedninja\RunescapeAPI\Player('codedninja');

        $this->assertNotNull($player->getClan());
    }

    public function testPlayerHasNoClan(): void
    {
        $player = new Tehcodedninja\RunescapeAPI\Player('codedninja2');

        $this->assertNull($player->getClan());
    }
}