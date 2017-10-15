<?php

namespace SteamRep;

/**
 * Class Reputation
 * @package SteamRep
 */
class SteamRepResponse extends SteamRepModel {
    public function getReputation(): Reputation {
        return new Reputation($this->body['steamrep']['reputation'] ?? []);
    }

    /**
     * if false, status is not 'exists' and is probably 'notfound'
     * Use for sanity checks.
     * @return bool
     */
    public function isFound(): bool {
        return $this->body['steamrep']['flags']['status'] === 'exists';
    }

    public function getSteamId32(): string {
        return $this->body['steamrep']['steamID32'];
    }

    public function getSteamId64(): string {
        return $this->body['steamrep']['steamID64'];
    }

    public function getSteamRepUrl(): string {
        return $this->body['steamrep']['steamrepurl'];
    }
}