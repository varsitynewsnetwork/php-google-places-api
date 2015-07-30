<?php

use Vnn\Places\Formatter\LatLngFormatter;

describe('Vnn\Places\Formatter\LatLngFormatter', function () {
    describe('__invoke()', function () {
        it('should format a single result', function () {
            $data = [
                'formatted_address' => '123 main st',
                'geometry' => [
                    'location' => [
                        'lat' => 5,
                        'lng' => 9
                    ]
                ]
            ];
            $expected = [
                'address' => '123 main st',
                'lat' => 5,
                'lng' => 9
            ];
            $formatter = new LatLngFormatter(true);
            $result = $formatter($data);

            expect($result)->to->equal($expected);
        });

        it('should remove the country on multiple results', function () {
            $data = [
                [
                    'formatted_address' => '123 main st',
                    'geometry' => [
                        'location' => [
                            'lat' => 5,
                            'lng' => 9
                        ]
                    ]
                ],
                [
                    'formatted_address' => '862 first st',
                    'geometry' => [
                        'location' => [
                            'lat' => 55,
                            'lng' => 12
                        ]
                    ]
                ]
            ];
            $expected = [
                [
                    'address' => '123 main st',
                    'lat' => 5,
                    'lng' => 9
                ],
                [
                    'address' => '862 first st',
                    'lat' => 55,
                    'lng' => 12
                ],
            ];
            $formatter = new LatLngFormatter();
            $result = $formatter($data);

            expect($result)->to->equal($expected);
        });
    });
});
