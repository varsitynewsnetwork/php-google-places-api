<?php

describe('Vnn\Services\Location\Google\PlacesService', function () {

    beforeEach(function () {
        $this->places_api_key = "ENTER GOOGLE PLACES API KEY HERE";
    });

    describe('->getGooglePlaceAPIUrl()', function () {

        it('should return a properly encoded URL', function () {
            $service = new Vnn\Services\Location\Google\PlacesService();
            $url = $service->getGooglePlaceAPIUrl("foo bar");
            assert($url == "https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo+bar&key=");

            $url = $service->getGooglePlaceAPIUrl("http://example.com?arg=value&arg2=value2");
            assert($url == "https://maps.googleapis.com/maps/api/place/textsearch/json?query=http%3A%2F%2Fexample.com%3Farg%3Dvalue%26arg2%3Dvalue2&key=");
        });
    });

    // skip the test for actually looking up information
    xdescribe('->lookupLocation()', function () {
        it('should return a valid address, lat, and long', function () {
            $service = new Vnn\Services\Location\Google\PlacesService($this->places_api_key);
            $testAddress = "12002 Jones Maltsberger Rd 78216 TX";

            $retVal = $service->lookupLocation($testAddress);

            assert($retVal['address'] == "12002 Jones Maltsberger Rd, San Antonio, TX 78216");
            assert($retVal['lat'] == "29.5499212");
            assert($retVal['lng'] == "-98.4652126");

            $retVal = $service->lookupLocation($testAddress, true);
            assert($retVal['address'] == "12002 Jones Maltsberger Rd, San Antonio, TX 78216, USA");
        });
    });

});
