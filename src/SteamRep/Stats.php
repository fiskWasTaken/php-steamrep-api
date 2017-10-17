<?php

namespace SteamRep;

/**
 * Class Stats
 * @package SteamRep
 */
class Stats extends SteamRepModel {
    public function getBannedFriendCount(): int {
        return $this->body['bannedfriends'] ?? 0;
    }

    public function getUnconfirmedReportsCount(): int {
        return $this->body['unconfirmedreports']['reportcount'] ?? 0;
    }
}