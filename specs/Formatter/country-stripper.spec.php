<?php

use Vnn\Places\Formatter\CountryStripperFormatter;

describe('Vnn\Places\Formatter\CountryStripperFormatter', function () {
    describe('__invoke()', function () {
        it('should remove the country on a single result', function () {
            $data = ['formatted_address' => '1, 2, 3'];
            $formatter = new CountryStripperFormatter(true);
            $result = $formatter($data);

            expect($result)->to->equal(['formatted_address' => '1, 2']);
        });

        it('should remove the country on multiple results', function () {
            $input = [
                ['formatted_address' => '1, 2, 3'],
                ['formatted_address' => '5, 6, 2']
            ];
            $expected = [
                ['formatted_address' => '1, 2'],
                ['formatted_address' => '5, 6']
            ];

            $formatter = new CountryStripperFormatter();
            $result = $formatter($input);

            expect($result)->to->equal($expected);

        });
    });
});
