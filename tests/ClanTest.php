<?php

use PHPUnit\Framework\TestCase;

class ClanTest extends TestCase {

    public function testClanInstance(): void
    {
        $clan = new Tehcodedninja\RunescapeAPI\Clan('Maxed');

        $this->assertInstanceOf(Tehcodedninja\RunescapeAPI\Clan::class, new Tehcodedninja\RunescapeAPI\Clan('Maxed'));
    }

    public function testClanExist(): void
    {
        $clan = new Tehcodedninja\RunescapeAPI\Clan('Maxed');

        $this->assertEquals('Maxed', $clan->getName());
    }

    public function testClanDoesntExist(): void
    {
        $this->expectException(Tehcodedninja\RunescapeAPI\Exception\UnknownClanException::class);
        
        $clan = new Tehcodedninja\RunescapeAPI\Clan('ClanDoesntExist');
    }

    public function testClanHasMembers(): void
    {
        $clan = new Tehcodedninja\RunescapeAPI\Clan('Maxed');

        $this->assertNotEmpty($clan);
    }
    
}