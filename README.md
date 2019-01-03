# google-places-api

A mini library to fetch place information out of Google Places API via a text search or a find place request
![Build Status](https://travis-ci.org/varsitynewsnetwork/php-google-places-api.svg?branch=master)

## Usage

```php
$service = new PlaceService(new GuzzleAdapter());
$service->setApiKey('YOUR_KEY_HERE');

$results = $service->textSearch('Van Andel Arena');
```

### Formatters

The concept of formatters are baked in to allow you to easily manipulate
the data returned by Google. Simply pass a callable as the second argument
to `textSearch()` or `findPlace()`.

For instance, if you only wanted the address of the first result:

```php
$service = new PlaceService(new GuzzleAdapter());
$service->setApiKey('YOUR_KEY_HERE');

$results = $service->textSearch('Van Andel Arena', function (results) {
    if (count($results)) {
        return $results[0]['formatted_address'];
    }

    return null;
});
```

The library also ships with some standard formatters:

 1. `CountryStripperFormatter`: Removes the country from the `formatted_address`
 1. `LatLngFormatter`: Formats the results as an array of lat, lng, and address.
 1. `SingleResultFormatter`: Grabs the first result and returns it
 1. `CompositeFormatter`: Allows for running multiple formatters

Example:

```php
$service = new PlaceService(new GuzzleAdapter());
$service->setApiKey('YOUR_KEY_HERE');

$result = $service->textSearch('Van Andel Arena', new CompositeFormatter([
    new SingleResultFormatter(),
    new CountryStripperFormatter(true),
    new LatLngFormatter(true)
]));
```

Which will yield something like:

```text
Array
(
    [address] => 130 Fulton West, Grand Rapids, MI 49503
    [lat] => 42.962433
    [lng] => -85.671566
)
```
