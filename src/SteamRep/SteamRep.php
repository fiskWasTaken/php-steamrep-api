<?php

namespace SteamRep;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class SteamRep
 * @package SteamRep
 */
class SteamRep {
    /**
     * @var Client
     */
    private $client;

    /**
     * SteamRep client constructor. You may override certain Guzzle client
     * options if you need to access the SteamRep API via a forward proxy or
     * increase request timeout.
     *
     * @param array $options
     */
    public function __construct(array $options = []) {
        $defaults = [
            'base_uri' => 'https://steamrep.com',
            'timeout' => 3
        ];

        $this->client = new Client($defaults + $options);
    }

    /**
     * Get reputation data for this user.
     * This will throw a GuzzleException for client/server issues, and SteamRepException for response errors such as
     * invalid status codes or unreadable JSON data.
     *
     * @param string $steamid64
     * @return SteamRepResponse
     * @throws GuzzleException
     * @throws SteamRepException
     */
    public function getUser(string $steamid64): SteamRepResponse {
        $response = $this->client->get("/api/beta4/reputation/{$steamid64}/?tagdetails=1&json=1");

        if ($response->getStatusCode() !== 200) {
            $exc = new SteamRepException("Unexpected status code for response: {$response->getStatusCode()}");
            $exc->setResponse($response);
            throw $exc;
        }

        $json = $response->getBody();
        $body = json_decode($json, true);

        if (!$body) {
            $exc = new SteamRepException("Empty JSON body, perhaps malformed?");
            $exc->setResponse($response);
            throw $exc;
        }

        return new SteamRepResponse($body);
    }
}