<?php

include_once __DIR__ . '/../vendor/autoload.php';

use SteamRep\SteamRep;

$client = new SteamRep();
$response = $client->getReputation("76561197971691194");

$tags = $response->getReputation()->getTags();

foreach ($tags as $tag) {
    print("{$tag->getAuthority()} {$tag->getStatus()}\n"); // SR ADMIN
}