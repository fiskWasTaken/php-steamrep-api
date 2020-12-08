<?php

namespace SteamRep;

use PHPUnit\Framework\TestCase;

class SteamRepResponseTest extends TestCase {
    /**
     * @var SteamRepResponse
     */
    private $mattie;

    /**
     * @var SteamRepResponse
     */
    private $fisk;

    /**
     * @var SteamRepResponse
     */
    private $frankie;

    /**
     * @var SteamRepResponse
     */
    private $notfound;

    /**
     * @var SteamRepResponse
     */
    private $badUnicode;

    private function load($path): SteamRepResponse {
        $resource = fopen($path, 'r');
        $data = sr_json_decode(fread($resource, filesize($path)), true);
        return new SteamRepResponse($data);
    }

    public function setUp(): void {
        $this->mattie = $this->load('./tests/mattie.json');
        $this->fisk = $this->load('./tests/fisk.json');
        $this->frankie = $this->load('./tests/frankie.json');
        $this->notfound = $this->load('./tests/notfound.json');
        $this->badUnicode = $this->load('./tests/scammer.json');
    }

    public function testBadUnicode() {
        // fixme: malformed characters will persist in display name, but whatever.
        $this->assertEquals("SCAMMER", $this->badUnicode->getReputation()->getSummary());
    }

    public function testTags() {
        $reputation = $this->mattie->getReputation();
        $tags = $reputation->getTags();

        $this->assertEquals('SR', $tags[0]->getAuthority());
        $this->assertEquals('ADMIN', $tags[0]->getStatus());
        $this->assertEquals('trusted', $tags[0]->getCategory());

        $this->assertEquals('REDDIT', $tags[1]->getAuthority());
        $this->assertEquals('ADMIN', $tags[1]->getStatus());
        $this->assertEquals('trusted', $tags[1]->getCategory());

        $this->assertEquals('SR', $tags[2]->getAuthority());
        $this->assertEquals('DONATOR', $tags[2]->getStatus());
        $this->assertEquals('misc', $tags[2]->getCategory());

        $this->assertEquals(1327282684, $tags[0]->getTimestamp());
        $this->assertEquals(1327282684, $tags[0]->getDateTime()->getTimestamp());
    }

    public function testSummary() {
        $reputation = $this->mattie->getReputation();
        $this->assertEquals('ADMIN', $reputation->getSummary());
        $this->assertEquals("REDDIT ADMIN,SR ADMIN,SR DONATOR", $reputation->getTagString());
    }

    public function testIds() {
        $this->assertEquals('76561197971691194', $this->mattie->getSteamId64());
        $this->assertEquals('STEAM_0:0:5712733', $this->mattie->getSteamId32());
        $this->assertEquals('http://steamrep.com/profiles/76561197971691194', $this->mattie->getSteamRepUrl());
    }

    public function testFound() {
        $this->assertEquals(true, $this->mattie->isFound());
        $this->assertEquals(false, $this->notfound->isFound());
    }

    public function testLastSyncTime() {
        $this->assertEquals(0, $this->notfound->getLastSyncTime());
        $this->assertEquals(1508096994, $this->fisk->getLastSyncTime());
    }

    /**
     * Test our safeguard against bad JSON structure for users with single tags
     */
    public function testMalformedSingleTagAccess() {
        $tags = $this->fisk->getReputation()->getTags();
        $this->assertEquals('BAZAAR', $tags[0]->getAuthority());
    }

    public function testStats() {
        $this->assertEquals(2, $this->fisk->getStats()->getBannedFriendCount());
        $this->assertEquals(0, $this->fisk->getStats()->getUnconfirmedReportsCount());
        $this->assertEquals(1, $this->frankie->getStats()->getUnconfirmedReportsCount());
    }
}