<?php

namespace SteamRep;


abstract class SteamRepModel {
    protected $body;

    public function __construct(array $body) {
        $this->body = $body;
    }
}