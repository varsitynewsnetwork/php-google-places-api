<?php

use Prophecy\Prophet;
use Vnn\Places\Client\ClientInterface;
use Vnn\Places\PlaceService;

describe('Vnn\Places\PlaceService', function () {
    beforeEach(function () {
        $this->prophet = new Prophet();
        $this->client = $this->prophet->prophesize(ClientInterface::class);
        $this->service = new PlaceService($this->client->reveal(), ['key' => 'master']);
    });

    describe('search()', function () {
        it('should query Google for the requested place', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo&key=master')
                ->willReturn(9);
            $result = $this->service->search('foo');

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should encode the query param', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/textsearch/json?query=foo+bar+city&key=master')
                ->willReturn(9);
            $this->service->search('foo bar city');

            $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/textsearch/json?query=&key=master')
                ->willReturn(9);
            $result = $this->service->search('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });


    describe('detail()', function () {
        it('should query Google for the requested place', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/details/json?placeid=foo&key=master')
                ->willReturn(9);
            $result = $this->service->detail('foo');

            expect($result)->to->equal(9);
            $this->prophet->checkPredictions();
        });

        it('should run the result through the passed formatter', function () {
            $this->client->fetch('https://maps.googleapis.com/maps/api/place/details/json?placeid=&key=master')
                ->willReturn(9);
            $result = $this->service->detail('', function ($result) {
                return $result * 2;
            });

            expect($result)->to->equal(18);
        });
    });
});
