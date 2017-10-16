<?php

namespace SteamRep;

use PHPUnit\Framework\TestCase;

class SteamRepResponseTest extends TestCase {
    /**
     * @var SteamRepResponse
     */
    private $instance;

    /**
     * @var SteamRepResponse
     */
    private $instance2;

    private function setUpNormalInstance() {
        $filename = './tests/mattie.json';
        $resource = fopen($filename, 'r');
        $data = json_decode(fread($resource, filesize($filename)), true);
        $this->instance = new SteamRepResponse($data);
    }

    private function setUpMalformedInstance() {
        $filename = './tests/fisk.json';
        $resource = fopen($filename, 'r');
        $data = json_decode(fread($resource, filesize($filename)), true);
        $this->instance2 = new SteamRepResponse($data);
    }

    public function setUp() {
        $this->setUpNormalInstance();
        $this->setUpMalformedInstance();
    }

    public function testTags() {
        $reputation = $this->instance->getReputation();
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
        $reputation = $this->instance->getReputation();
        $this->assertEquals('ADMIN', $reputation->getSummary());
    }

    public function testIds() {
        $this->assertEquals('76561197971691194', $this->instance->getSteamId64());
        $this->assertEquals('STEAM_0:0:5712733', $this->instance->getSteamId32());
        $this->assertEquals('http://steamrep.com/profiles/76561197971691194', $this->instance->getSteamRepUrl());
    }

    public function testFound() {
        $this->assertEquals(true, $this->instance->isFound());
    }

    public function testMalformedSingleTagAccess() {
        $tags = $this->instance2->getReputation()->getTags();
        $this->assertEquals('BAZAAR', $tags[0]->getAuthority());
    }
}