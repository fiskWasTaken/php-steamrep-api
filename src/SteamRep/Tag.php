<?php

namespace SteamRep;

use DateTime;

/**
 * Class Tag
 * @package SteamRep
 */
class Tag extends SteamRepModel {
    public function getName(): string {
        return $this->body['name'];
    }

    public function getTimestamp(): int {
        return (int)$this->body['timestamp'];
    }

    /**
     * Dates are presented in the API as Y-m-d H:i:s, e.g. 2012-01-22 19:38:04
     * Unfortunately this doesn't include the time zone, which appears to be CST.
     * To resolve this ambiguity we disregard this value and just use the timestamp,
     * which we know resolves to UTC. If you really need "SteamRep time", you can
     * set the time zone of the returned DateTime object to CST6CDT.
     *
     * @return \DateTime
     */
    public function getDateTime(): \DateTime {
        return new DateTime("@{$this->body['timestamp']}");
    }

    /**
     * Possible category values and their applied usages are as follows:
     * "trusted" -- indicates a trusted user group; partner community, middleman, SteamRep admin, etc.
     * "misc"    -- used for the SteamRep donator label.
     * "evil"    -- scammer tags.
     * "warning" -- caution tags.
     *
     * @return string
     */
    public function getCategory(): string {
        return $this->body['category'];
    }

    /**
     * Get the name of the authority responsible for the tag.
     * I've never seen this ever be more than a single word, and I
     * don't think that will change.
     *
     * @return string
     */
    public function getAuthority(): string {
        return explode(' ', $this->getName())[0];
    }

    /**
     * Get the tag status (SCAMMER, ADMIN, etc).
     * As with the authority, we don't expect the "AUTHORITY TYPE"
     * tag format to change.
     *
     * @return string
     */
    public function getStatus(): string {
        return explode(' ', $this->getName())[1];
    }
}