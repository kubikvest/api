<?php

namespace Kubikvest\Model;

class PointTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new Point();

        $this->assertInstanceOf('\\Kubikvest\\Model\\Point', $actual);
    }

    /**
     * @dataProvider checkCoordinatesDataProvider
     */
    public function testCheckCoordinates($data, $expected)
    {
        $point = new Point();
        $point->coords = $data['coords'];
        $actual = $point->checkCoordinates($data['lat'], $data['lng']);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider calcDistanceToPointsSectorDataProvider
     */
    public function testCalcDistanceToPointsSector($data, $expected)
    {
        $point = new Point();
        $point->coords = $data['coords'];
        $actual = $point->calcDistanceToPointsSector($data['lat'], $data['lng']);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @dataProvider checkAccuracyDataProvider
     */
    public function testCheckAccuracy($accuracy, $distance, $expected)
    {
        $point = new Point();
        $actual = $point->checkAccuracy($accuracy, $distance);

        $this->assertEquals($expected, $actual);
    }

    public function checkAccuracyDataProvider()
    {
        return [
            [
                'accuracy' => Point::ACCURACY_MAX,
                'distance' => 23,
                'expected' => true,
            ],
            [
                'accuracy' => 12,
                'distance' => 12,
                'expected' => true,
            ],
            [
                'accuracy' => 12,
                'distance' => 23,
                'expected' => false,
            ],
            [
                'accuracy' => Point::ACCURACY_MAX + 1,
                'distance' => 23,
                'expected' => false,
            ],
            [
                'accuracy' => Point::ACCURACY_MAX,
                'distance' => 100,
                'expected' => false,
            ],
            [
                'accuracy' => Point::ACCURACY_MAX + 1,
                'distance' => 100,
                'expected' => false,
            ],
        ];
    }

    public function checkCoordinatesDataProvider()
    {
        return [
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [-89.9999, 89.9999],
                        'longitude' => [-179.9999, 179.999],
                    ],
                    'lat' => 0,
                    'lng' => 0,
                ],
                'expected' => true,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [50.9999, 55.9999],
                        'longitude' => [30.9999, 35.9999],
                    ],
                    'lat' => 51.1234,
                    'lng' => 33.4321,
                ],
                'expected' => true,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [50.9999, 55.9999],
                        'longitude' => [30.9999, 35.9999],
                    ],
                    'lat' => 51.1234,
                    'lng' => 30.9998,
                ],
                'expected' => false,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [50.9999, 55.9999],
                        'longitude' => [30.9999, 35.9999],
                    ],
                    'lat' => 50.9998,
                    'lng' => 33.4321,
                ],
                'expected' => false,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [50.9999, 55.9999],
                        'longitude' => [30.9999, 35.9999],
                    ],
                    'lat' => 50.9999,
                    'lng' => 30.9999,
                ],
                'expected' => true,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [50.9999, 55.9999],
                        'longitude' => [30.9999, 35.9999],
                    ],
                    'lat' => 55.9999,
                    'lng' => 35.9999,
                ],
                'expected' => true,
            ],
        ];
    }

    public function calcDistanceToPointsSectorDataProvider()
    {
        return [
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [54.9999, 55.9999],
                        'longitude' => [34.9999, 35.9999],
                    ],
                    'lat' => 55.1234,
                    'lng' => 35.4321,
                ],
                'expected' => [
                    30758.487912625915,
                    38677.751485956818,
                    101180.25388819606,
                    103795.75497560695,
                ],
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [54.9999, 55.9999],
                        'longitude' => [34.9999, 35.9999],
                    ],
                    'lat' => 54.9999,
                    'lng' => 35.9999,
                ],
                'expected' => [
                    63778.405548902178,
                    0,
                    127789.64194341243,
                    111194.92664455985,
                ],
            ],
        ];
    }
}
