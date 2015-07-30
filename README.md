# google-places-api

A mini library to fetch place information out of Google Places API via a text search


## Usage

```php
$service = new PlaceService(new GuzzleAdapter());
$service->setApiKey('YOUR_KEY_HERE');

$results = $service->search('Van Andel Arena');
```
