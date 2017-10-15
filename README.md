# SteamRep PHP bindings

Provides bindings for the public SteamRep web APIs to fetch reputation for a user.

## Composer

```
composer require fisk/steamrep
```

## Usage  

Typical usage is as follows:

```
<?php
use SteamRep\SteamRep;

$client = new SteamRep();
$response = $client->getReputation("76561197971691194");

$tags = $response->getReputation()->getTags();

foreach ($tags as $tag) {
    print("{$tag->getAuthority()} {$tag->getStatus()}\n"); // SR ADMIN
}
?>
```

### Tags

SteamRep tags are formatted as follows:

```
SR SCAMMER
TF2OP ADMIN
SOP CAUTION
```

This library supplies some helper methods to extract the authority and status for each tag.
See the PHPDoc for more information.

### Error handling

* The client will throw a `GuzzleException` for Guzzle client errors, or a `SteamRepException` if 
the returned data is malformed.

* Check the `SteamRepResponse` `isFound()` method if you aren't totally sure about 
your inputs. This will be true if the requested SteamID64 is valid.