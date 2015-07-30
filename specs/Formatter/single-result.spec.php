<?php

use Vnn\Places\Formatter\SingleResultFormatter;

describe('Vnn\Places\Formatter\SingleResultFormatter', function () {
    describe('__invoke()', function () {
        it('should return the first element of an array', function () {
            $formatter = new SingleResultFormatter();
            $result = $formatter([3, 8, 9]);
            expect($result)->to->equal(3);
        });

        it('should return the passed data if empty', function () {
            $formatter = new SingleResultFormatter();
            $result = $formatter([]);
            expect($result)->to->equal([]);
        });
    });
});
