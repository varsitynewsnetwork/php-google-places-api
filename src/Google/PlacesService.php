<?php

namespace Vnn\Services\Location\Google;

class PlacesService
{

   /**
     *
     * @var string $apiKey
     */
    protected $apiKey;

    public function __construct($key)
    {
        $this->apiKey = $key;
    }

    public function getGooglePlaceAPIUrl($locationString)
    {
        $googleBaseUrl = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=";
        return $googleBaseUrl . urlencode($locationString) . "&key=" . $this->apiKey;
    }

    /**
     * Looks up the location passed in the Google Places API via text search
     * and returns the formatted address string, latitiude, and longitude, if available.
     *
     * @param  string $locationString The string to look up as a Google Places location
     * @param  boolean $keepCountry Whether or not to include the last piece of the formatted address (country)
     * @return array Keys are 'address', 'lat', 'lng'
     */
    public function lookupLocation($locationString, $keepCountry = false)
    {
        $retval = ['address' => null, 'lat' => null, 'lng' => null];
        $googleUrl = $this->getGooglePlaceAPIUrl($locationString);

        $client = new \GuzzleHttp\Client();
        $res = $client->get($googleUrl);
        if ($res->getStatusCode() != 200) {
            // log error?
            return $retval;
        }
        $response = $res->getBody()->getContents();

        $json = json_decode($response);

        // check to make sure we have valid json, and also that there's no error
        if ($json != false && $json->status == "OK") {
            if (count($json->results) > 0) {
                // return the first result, because it's the best match
                $result = $json->results[0];

                // should we keep the country identifier at the end of an address?
                if ($keepCountry) {
                    $retval['address'] = $result->formatted_address;
                } else {
                    $addressPieces = explode(",", $result->formatted_address);
                    array_pop($addressPieces);
                    $retval['address'] = implode(",", $addressPieces);
                }

                if ($result->geometry && $result->geometry->location) {
                    $retval['lat'] = $result->geometry->location->lat;
                    $retval['lng'] = $result->geometry->location->lng;
                }
            }
        }

        return $retval;
    }

}