<?php

use Vnn\Places\Formatter\CompositeFormatter;

describe('Vnn\Places\Formatter\CompositeFormatter', function () {
    describe('__invoke()', function () {
        it('should run every formatter', function () {
            $results = [];
            $expected = [3, 6, 9];
            $callback = function ($data) use (&$results) {
                $data = $data[0] + 3;
                array_push($results, $data);
                return [$data];
            };
            $formatter = new CompositeFormatter([
                $callback,
                $callback,
                $callback
            ]);

            $final = $formatter([0]);

            expect($final[0])->to->equal(9);
            expect($results)->to->equal($expected);
        });
    });
});
