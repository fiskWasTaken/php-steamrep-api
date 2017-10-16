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
        $tags = $this->body['tags']['tag'] ?? [];

        /*
         * This fixes a stupid bug in the API where a user with a single tag will have the tag structured as:
         *
         * tags.tag.name
         *
         * instead of:
         *
         * tags.tag[].name
         */
        if (array_key_exists('name', $tags)) {
            $tags = [$tags];
        }

        return array_map(function($data) {
            return new Tag($data);
        }, $tags);
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
        return $this->body['summary'] ?? "none";
    }

    public function getTagString(): string {
        return $this->body['full'] ?? '';
    }
}