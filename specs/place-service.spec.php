<?php

use Prophecy\Prophet;
use Vnn\Places\Client\ClientInterface;
use Vnn\Places\PlaceService;

describe('Vnn\Places\PlaceService', function () {
    beforeEach(function () {
        $this->prophet = new Prophet();
        $this->client = $this->prophet->prophesize(ClientInterface::class);
        
        $this->googleApiKey = 'testKey';
        
        $this->service = new PlaceService(
            $this->client->reveal(),
            $this->googleApiKey
        );
    });

    describe('textSearch()', function () {
        it('should query Google for the requested place', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo&key='.$this->googleApiKey;
            
            $this->client->fetch($apiUrl)
                ->willReturn(['results' => 9]);
            
            $result = $this->service->textSearch('foo');

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should encode the query param', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo+bar+city&key=master')
                ->willReturn(['results' => 9])->shouldBeCalled();
            $this->service->textSearch('foo bar city');

            $this->prophet->checkPredictions();
        });

        it('should encode optional parameters', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo+bar+city&key='.$this->googleApiKey.'&foo=bar%26baz';
            
            $this->client->fetch($apiUrl)->willReturn(['results' => 9])->shouldBeCalled();

            $this->service->textSearch('foo bar city', null, ['foo' => 'bar&baz']);
            $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/textsearch/json?query=&key='.$this->googleApiKey;
            
            $this->client->fetch($apiUrl)
                ->willReturn(['results' => 9]);
            
            $result = $this->service->textSearch('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });

    describe('findPlace()', function () {
        it('should query Google for the requested place', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key='.$this->googleApiKey.'&input=foo&inputtype=textquery';
            
            $this->client->fetch($apiUrl)
                ->willReturn(['candidates' => 9]);
            $result = $this->service->findPlace('foo');

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should encode the input param', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key='.$this->googleApiKey.'&input=foo+bar+city&inputtype=textquery';
            
            $this->client->fetch($apiUrl)
                ->willReturn(['candidates' => 9])->shouldBeCalled();
            $this->service->findPlace('foo bar city');

            $this->prophet->checkPredictions();
        });

        it('should encode optional params', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key='.$this->googleApiKey.'&input=foo&inputtype=textquery&bar=baz+biz';
            
            $this->client->fetch($apiUrl)
                ->willReturn(['candidates' => 9])->shouldBeCalled();
            $this->service->findPlace('foo', null, null, ['bar' => 'baz biz']);

            $this->prophet->checkPredictions();
        });

        it('should request output fields if specified', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?' .
                'key='.$this->googleApiKey.'&input=foo&inputtype=textquery&fields=formatted_address,name,geometry'
            
            $this->client->fetch($apiUrl)
                ->willReturn(['candidates' => 9]);
           $result = $this->service->findPlace('foo', null, ['formatted_address', 'name', 'geometry']);

           expect($result)->to->equal(9);
           $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key='.$this->googleApiKey.'&input=&inputtype=textquery';
            
            $this->client->fetch($apiUrl)
                ->willReturn(['candidates' => 9]);
            
            $result = $this->service->findPlace('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });

    describe('detail()', function () {
        it('should query Google for the requested place', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=foo&key='.$this->googleApiKey;
            
            $this->client->fetch($apiUrl)
                ->willReturn(['result' => 9]);
            $result = $this->service->detail('foo');

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should encode the optional params', function () {
            
            $apiUrl = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=foo&key='.$this->googleApiKey.'&bar=biz+baz';
            
            $this->client->fetch($apiUrl)
                ->willReturn(['result' => 9]);
            $result = $this->service->detail('foo', null, null, ['bar' => 'biz baz']);

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should request output fields if specified', function () {
            
            $apiKey = 'https://maps.googleapis.com/maps/api/place/details/json?' .
                'placeid=foo&key='.$this->googleApiKey.'&fields=formatted_address,name,geometry';
            
            $this->client->fetch($apiKey)
                ->willReturn(['result' => 9]);
            
            $result = $this->service->detail('foo', null, ['formatted_address', 'name', 'geometry']);

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            
            $apiKey = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=&key='.$this->googleApiKey;
            
            $this->client->fetch($apiKey)
                ->willReturn(['result' => 9]);
            $result = $this->service->detail('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });
});
