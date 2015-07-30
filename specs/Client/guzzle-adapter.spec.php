<?php

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Prophecy\Prophet;
use Vnn\Places\Client\GuzzleAdapter;

describe('Vnn\Places\Client\GuzzleAdapter', function () {
    beforeEach(function () {
        $this->prophet = new Prophet();
        $this->guzzle = $this->prophet->prophesize(Client::class);
        $this->adapter = new GuzzleAdapter($this->guzzle->reveal());
    });

    describe('fetch()', function () {
        it('should make an HTTP GET request', function () {
            $this->guzzle->get('/foo')->willReturn(new Response(200, [], '{"status": "OK", "results": []}'));
            $result = $this->adapter->fetch('/foo');

            expect($result)->to->equal([]);

            $this->prophet->checkPredictions();
        });

        it('should return the results as an array', function () {
            $this->guzzle->get('/foo')->willReturn(
                (new Response(200, [], '{"status": "OK", "results": [1, 2, 3]}'))
            );
            $result = $this->adapter->fetch('/foo');

            expect($result)->to->equal([1, 2, 3]);
        });

        it('should throw an exception of the result is empty or not valid JSON', function () {
            $message = null;
            $this->guzzle->get('/foo')
                ->willReturn(new Response(401, [], ''));

            try {
                $this->adapter->fetch('/foo');
            } catch (RuntimeException $e) {
                $message = $e->getMessage();
            }

            expect($message)->to->equal('Failed to parse response');
        });

        it('should throw an exception if the result status is not OK', function () {
            $message = null;
            $this->guzzle->get('/foo')
                ->willReturn(new Response(401, [], '{"status": "DENIED", "error_message": "BOOM!"}'));

            try {
                $this->adapter->fetch('/foo');
            } catch (RuntimeException $e) {
                $message = $e->getMessage();
            }

            expect($message)->to->equal('BOOM!');
        });
    });
});
