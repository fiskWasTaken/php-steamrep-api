<?php

namespace SteamRep;

/**
 * Class Reputation
 * @package SteamRep
 */
class Reputation extends SteamRepModel {
    /**
     * @return Tag[]
     */
    public function getTags() {
        return array_map(function($data) {
            return new Tag($data);
        }, $this->body['tags']['tag'] ?? []);
    }

    /**
     * Possible values for summaries can be one of the following, pay attention to the case:
     * "none"
     * "SCAMMER"
     * "CAUTION"
     * "ADMIN"
     * "MIDDLEMAN"
     * "TRUSTED SELLER"
     *
     * @return string
     */
    public function getSummary(): string {
        return $this->body['summary'];
    }
}