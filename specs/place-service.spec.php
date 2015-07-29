<?php


describe('Vnn\Services\Location\Google\PlacesService', function () {

    it('should return a properly encoded URL', function () {
        $this->service = new Vnn\Services\Location\Google\PlacesService();
        $url = $this->service->getGooglePlaceAPIUrl("foo bar");
        assert($url == "https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo+bar&key=");

        $url = $this->service->getGooglePlaceAPIUrl("http://example.com?arg=value&arg2=value2");
        assert($url == "https://maps.googleapis.com/maps/api/place/textsearch/json?query=http%3A%2F%2Fexample.com%3Farg%3Dvalue%26arg2%3Dvalue2&key=");
    });

});
