<?php

include_once __DIR__ . '/../vendor/autoload.php';

use SteamRep\SteamRep;

$client = new SteamRep();
$response = $client->getUser("76561197971691194");

printf(
    "%s has %d banned friends.\n",
    $response->getSteamId64(),
    $response->getStats()->getBannedFriendCount()
);