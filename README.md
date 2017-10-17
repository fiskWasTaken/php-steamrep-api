# SteamRep PHP API

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
$response = $client->getUser("76561197971691194");

$tags = $response->getReputation()->getTags();

foreach ($tags as $tag) {
    print("{$tag->getAuthority()} {$tag->getStatus()}\n"); // SR ADMIN
}
```

### SteamRepResponse

`SteamRepResponse` is the response object for a successful call.

#### isValid(): bool

Although a call to the SteamRep API may be successful, the input SteamId64 may
be incorrect, or refers to a profile not yet tracked on SteamRep.

This asserts that the value of `steamrep.flags.status` is `valid`.

#### getLastSyncTime(): int

Get the time of the last update for the SteamRep profile.

#### getReputation(): Reputation

Returns a `Reputation` entity which provides helper functions for the `steamrep.reputation` document.

#### getStats(): Stats

Returns a `Stats` entity which provides helper functions for the `steamrep.stats` document.

### Reputation

The `Reputation` class exposes the reputation data for a user.

#### getSummary(): string

Returns the value provided by `steamrep.reputation.summary`. Known values are as follows:

* `none`
* `SCAMMER`
* `CAUTION`
* `ADMIN`
* `MIDDLEMAN`
* `TRUSTED SELLER` (disused)

#### getTagString(): string

A delimited list of tag names provided by `steamrep.reputation.full`.

Tags in the tag string are sorted by tag category, with miscellaneous tags appearing last.

#### getTags(): Tag[]

Returns an array of `Tag` objects representing the `steamrep.reputation.tags` document.

Tags in this array are sorted in chronological order.

### Tag

A `Tag` represents a SteamRep tag, which consists of an authority and a status. This library supplies some helper methods to extract this information.

#### getName(): string

Get the name for this tag, e.g. `SOP ADMIN`

#### getAuthority(): string

Get the authority for this tag, e.g. `SOP`

#### getStatus(): string

Get the status for this tag, e.g. `ADMIN`

#### getTimestamp(): int

Get the creation time of the tag as a UNIX timestamp.

#### getDateTime(): DateTime

Get the creation time of the tag as a PHP `DateTime` object.

**NB**: The SteamRep API provides a date string, however this does not include the timezone.
If you need "SteamRep time", set the timezone of the returned object to CST6CDT.

#### getCategory(): string

Get the category of the tag. This can be one of the following:

* `trusted` - indicates a trusted user group; partner community, middleman, SteamRep admin, etc.
* `misc`    - used for the SteamRep donator label.
* `evil`    - scammer tags.
* `warning` - caution tags.

### Stats

#### getBannedFriendsCount(): int

Return the number of friends with SCAMMER tags.

#### getUnconfirmedReportsCount(): int

Returns the count of unconfirmed reports (the number of forum threads with non-conclusive tags).

## Error handling

* The client will throw a `GuzzleException` for Guzzle client errors, or a `SteamRepException` if 
the returned data is malformed.

* Always check the `SteamRepResponse` `isFound()` method if you aren't totally sure about 
your inputs. This will be true if the requested SteamID64 is valid.
