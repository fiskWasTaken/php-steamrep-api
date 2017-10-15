<?php

namespace SteamRep;


use Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Class SteamRepException
 * Thrown for response body errors.
 * @package SteamRep
 */
class SteamRepException extends Exception {
    /**
     * @var ResponseInterface
     */
    protected $response;

    public function getResponse(): ResponseInterface {
        return $this->response;
    }

    public function setResponse(ResponseInterface $response) {
        $this->response = $response;
    }
}