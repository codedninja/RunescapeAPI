<?php

use PHPUnit\Framework\TestCase;

class ClanTest extends TestCase {

    public function testClanInstance(): void
    {
        $clan = new RunescapeAPI\Clan('Maxed');

        $this->assertInstanceOf(RunescapeAPI\Clan::class, new RunescapeAPI\Clan('Maxed'));
    }

    public function testClanExist(): void
    {
        $clan = new RunescapeAPI\Clan('Maxed');

        $this->assertEquals('Maxed', $clan->getName());
    }

    public function testClanDoesntExist(): void
    {
        $this->expectException(RunescapeAPI\Exception\UnknownClanException::class);
        
        $clan = new RunescapeAPI\Clan('ClanDoesntExist');
    }

    public function testClanHasMembers(): void
    {
        $clan = new RunescapeAPI\Clan('Maxed');

        $this->assertNotEmpty($clan);
    }
    
}