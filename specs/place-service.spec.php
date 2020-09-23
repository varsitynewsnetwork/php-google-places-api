<?php

use Prophecy\Prophet;
use Vnn\Places\Client\ClientInterface;
use Vnn\Places\PlaceService;

describe('Vnn\Places\PlaceService', function () {
    beforeEach(function () {
        $this->prophet = new Prophet();
        $this->client = $this->prophet->prophesize(ClientInterface::class);
        
        $googleApiKey = 'testKey';
        
        $this->service = new PlaceService(
            $this->client->reveal(),
            $googleApiKey
        );
    });

    describe('textSearch()', function () {
        it('should query Google for the requested place', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo&key=master')
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
            $this->client->fetch(
                'https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo+bar+city&key=master&foo=bar%26baz'
            )->willReturn(['results' => 9])->shouldBeCalled();

            $this->service->textSearch('foo bar city', null, ['foo' => 'bar&baz']);
            $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/textsearch/json?query=&key=master')
                ->willReturn(['results' => 9]);
            $result = $this->service->textSearch('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });

    describe('findPlace()', function () {
        it('should query Google for the requested place', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=master&input=foo&inputtype=textquery')
                ->willReturn(['candidates' => 9]);
            $result = $this->service->findPlace('foo');

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should encode the input param', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=master&input=foo+bar+city&inputtype=textquery')
                ->willReturn(['candidates' => 9])->shouldBeCalled();
            $this->service->findPlace('foo bar city');

            $this->prophet->checkPredictions();
        });

        it('should encode optional params', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=master&input=foo&inputtype=textquery&bar=baz+biz')
                ->willReturn(['candidates' => 9])->shouldBeCalled();
            $this->service->findPlace('foo', null, null, ['bar' => 'baz biz']);

            $this->prophet->checkPredictions();
        });

        it('should request output fields if specified', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?' .
                'key=master&input=foo&inputtype=textquery&fields=formatted_address,name,geometry')
                ->willReturn(['candidates' => 9]);
           $result = $this->service->findPlace('foo', null, ['formatted_address', 'name', 'geometry']);

           expect($result)->to->equal(9);
           $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/findplacefromtext/json?key=master&input=&inputtype=textquery')
                ->willReturn(['candidates' => 9]);
            $result = $this->service->findPlace('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });

    describe('detail()', function () {
        it('should query Google for the requested place', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/details/json?placeid=foo&key=master')
                ->willReturn(['result' => 9]);
            $result = $this->service->detail('foo');

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should encode the optional params', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/details/json?placeid=foo&key=master&bar=biz+baz')
                ->willReturn(['result' => 9]);
            $result = $this->service->detail('foo', null, null, ['bar' => 'biz baz']);

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should request output fields if specified', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/details/json?' .
                'placeid=foo&key=master&fields=formatted_address,name,geometry'
                )->willReturn(['result' => 9]);
            $result = $this->service->detail('foo', null, ['formatted_address', 'name', 'geometry']);

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/details/json?placeid=&key=master')
                ->willReturn(['result' => 9]);
            $result = $this->service->detail('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });
});
