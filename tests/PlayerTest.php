<?php

use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase {

    public function testPlayerInstance(): void
    {
        $player = new RunescapeAPI\Player('Codedninja');

        $this->assertInstanceOf(RunescapeAPI\Player::class, new RunescapeAPI\Player('codedninja'));
    }

    public function testPlayerExist(): void
    {
        $player = new RunescapeAPI\Player('codedninja');

        $this->assertEquals('codedninja', $player->getName());
    }

    public function testPlayerDoesntExist(): void
    {
        $this->expectException(RunescapeAPI\Exception\UnknownPlayerException::class);
        
        $player = new RunescapeAPI\Player('PlayerDoesntExist');
    }

    public function testPlayerHasClan(): void
    {
        $player = new RunescapeAPI\Player('codedninja');

        $this->assertNotNull($player->getClan());
    }

    public function testPlayerHasNoClan(): void
    {
        $player = new RunescapeAPI\Player('codedninja2');

        $this->assertNull($player->getClan());
    }
}