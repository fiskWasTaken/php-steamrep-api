<?php

namespace SteamRep;

/**
 * A wrapper for json_decode that handles malformed JSON.
 *
 * @param $json
 * @param bool $assoc
 * @param int $depth
 * @return mixed
 */
function sr_json_decode($json, $assoc = false, $depth = 512)
{
    $out = json_decode($json, $assoc, $depth, JSON_INVALID_UTF8_SUBSTITUTE);

    if (json_last_error() === JSON_ERROR_NONE) {
        return $out;
    }

    $json = preg_replace('/\\\\u[0-9A-F]{4}/i', '', $json);
    return json_decode($json, $assoc, $depth, JSON_INVALID_UTF8_SUBSTITUTE);
}