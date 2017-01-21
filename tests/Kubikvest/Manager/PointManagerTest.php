<?php

namespace Kubikvest\Mapper;

use Kubikvest\Manager\PointManager;

class PointManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PointManager
     */
    protected $manager;

    public function setUp()
    {
        $this->manager = new PointManager(new PointMapper([]));
    }

    public function testInstance()
    {
        $data['acr'] = 39;

        if (32.391073763276 > (int)$data['acr'] ||
            !$this->manager->pointIncludedAccuracyRange((int)$data['acr'], 32.407732383084)
        ){
            var_dump(11111);
        }


        $actual = new PointManager(new PointMapper([]));

        $this->assertInstanceOf(PointManager::class, $actual);
    }

    /**
     * @param array $data
     * @param bool  $expected
     *
     * @dataProvider checkCoordinatesDataProvider
     */
    public function testCheckCoordinates($data, $expected)
    {
        $actual = $this->manager->checkCoordinates($data['coords'], $data['lat'], $data['lng']);

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testCheckCoordinates
     * @return array
     */
    public function checkCoordinatesDataProvider()
    {
        return [
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [60.983826, 60.983902],
                        'longitude' => [25.658975, 25.659115],
                    ],
                    'lat' => 60.983826,
                    'lng' => 25.658975,
                ],
                'expected' => true,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [60.983826, 60.983902],
                        'longitude' => [25.658975, 25.659115],
                    ],
                    'lat' => 60.983850,
                    'lng' => 25.6590,
                ],
                'expected' => true,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [60.983826, 60.983902],
                        'longitude' => [25.658975, 25.659115],
                    ],
                    'lat' => 60.983826,
                    'lng' => 25.658974,
                ],
                'expected' => false,
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [60.983826, 60.983902],
                        'longitude' => [25.658975, 25.659115],
                    ],
                    'lat' => 60.983825,
                    'lng' => 25.658975,
                ],
                'expected' => false,
            ],
        ];
    }

    /**
     * @param array $data
     * @param bool  $expected
     *
     * @dataProvider pointIncludedAccuracyRangeDataProvider
     */
    public function testPointIncludedAccuracyRange($data, $expected)
    {
        $actual = $this->manager->pointIncludedAccuracyRange($data['accuracy'], $data['minDistance']);

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testPointIncludedAccuracyRange
     *
     * @return array
     */
    public function pointIncludedAccuracyRangeDataProvider()
    {
        return [
            [
                'data' => [
                    'accuracy'    => 40,
                    'minDistance' => 20,
                ],
                'expected' => true,
            ],
            [
                'data' => [
                    'accuracy'    => 20,
                    'minDistance' => 20,
                ],
                'expected' => true,
            ],
            [
                'data' => [
                    'accuracy'    => 41,
                    'minDistance' => 20,
                ],
                'expected' => false,
            ],
            [
                'data' => [
                    'accuracy'    => 20,
                    'minDistance' => 21,
                ],
                'expected' => false,
            ],
        ];
    }

    /**
     * @param array $data
     * @param array $expected
     * @dataProvider calcDistanceToPointsSectorDataProvider
     */
    public function testCalcDistanceToPointsSector($data, $expected)
    {
        $actual = $this->manager->calcDistanceToPointsSector($data['coords'], $data['lat'], $data['lng']);

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testCalcDistanceToPointsSector
     *
     * @return array
     */
    public function calcDistanceToPointsSectorDataProvider()
    {
        return [
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [-0.0001, 0.0001],
                        'longitude' => [-0.0001, 0.0001],
                    ],
                    'lat' => 0,
                    'lng' => 0,
                ],
                'expected' => [
                    '15.725337332778' => [
                        'distance' => '15.725337332778',
                        'latitude' => 0.0001,
                        'longitude' => 0.0001,
                    ]
                ],
            ],
            [
                'data' => [
                    'coords' => [
                        'latitude'  => [-0.0001, 0.0001],
                        'longitude' => [-0.0001, 0.0001],
                    ],
                    'lat' => -0.00015,
                    'lng' => 0.00015,
                ],
                'expected' => [
                    '28.34925503859' => [
                        'distance' => '28.34925503859',
                        'latitude' => -0.0001,
                        'longitude' => -0.0001,
                    ],
                    '7.8626686663813' => [
                        'distance' => '7.8626686663813',
                        'latitude' => -0.0001,
                        'longitude' => 0.0001,
                    ],
                    '39.313343331937' => [
                        'distance' => '39.313343331937',
                        'latitude' => 0.0001,
                        'longitude' => -0.0001,
                    ],
                    '28.349255038655' => [
                        'distance' => '28.349255038655',
                        'latitude' => 0.0001,
                        'longitude' => 0.0001,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $data
     * @param float $expected
     * @dataProvider distanceBorderSectorDataProvider
     */
    public function testDistanceBorderSector($data, $expected)
    {
        $actual = $this->manager->distanceBorderSector($data);

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testDistanceBorderSector
     *
     * @return array
     */
    public function distanceBorderSectorDataProvider()
    {
        return [
            [
                'data' => [
                    '28.34925503859' => [
                        'distance' => '28.34925503859',
                        'latitude' => -0.0001,
                        'longitude' => -0.0001,
                    ],
                    '7.8626686663813' => [
                        'distance' => '7.8626686663813',
                        'latitude' => -0.0001,
                        'longitude' => 0.0001,
                    ],
                    '39.313343331937' => [
                        'distance' => '39.313343331937',
                        'latitude' => 0.0001,
                        'longitude' => -0.0001,
                    ],
                    '28.349255038655' => [
                        'distance' => '28.349255038655',
                        'latitude' => 0.0001,
                        'longitude' => 0.0001,
                    ],
                ],
                'expected' => 5.5597463322531082,
            ],
        ];
    }

    /**
     * @param array $data
     * @param float $expected
     * @dataProvider vincentyGreatCircleDistanceDataProvider
     */
    public function testVincentyGreatCircleDistance($data, $expected)
    {
        $actual = $this->manager->vincentyGreatCircleDistance(
            $data['latitudeFrom'],
            $data['longitudeFrom'],
            $data['latitudeTo'],
            $data['longitudeTo']
        );

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testVincentyGreatCircleDistance
     *
     * @return array
     */
    public function vincentyGreatCircleDistanceDataProvider()
    {
        return [
            [
                'data' => [
                    'latitudeFrom'  => 0,
                    'longitudeFrom' => 0,
                    'latitudeTo'    => 0.0001,
                    'longitudeTo'   => 0.0001,
                ],
                'expected' => 15.725337332778,
            ],
        ];
    }

    /**
     * @param array $data
     * @param float $expected
     * @dataProvider altitudeTriangleDataProvider
     */
    public function testAltitudeTriangle($data, $expected)
    {
        $actual = $this->manager->altitudeTriangle($data['a'], $data['b'], $data['c']);

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testAltitudeTriangle
     *
     * @return array
     */
    public function altitudeTriangleDataProvider()
    {
        return [
            [
                'data' => [
                    'a' => 30,
                    'b' => 40,
                    'c' => 50,
                ],
                'expected' => 24.0,
            ],
        ];
    }

    /**
     * @param array $data
     * @param float $expected
     * @dataProvider nearestPointDataProvider
     */
    public function testNearestPoint($data, $expected)
    {
        $actual = $this->manager->nearestPoint($data);

        $this->assertSame($expected, $actual);
    }

    public function nearestPointDataProvider()
    {
        return [
            [
                'data' => [
                    '28.34925503859' => [
                        'distance' => '28.34925503859',
                        'latitude' => -0.0001,
                        'longitude' => -0.0001,
                    ],
                    '7.8626686663813' => [
                        'distance' => '7.8626686663813',
                        'latitude' => -0.0001,
                        'longitude' => 0.0001,
                    ],
                    '39.313343331937' => [
                        'distance' => '39.313343331937',
                        'latitude' => 0.0001,
                        'longitude' => -0.0001,
                    ],
                    '28.349255038655' => [
                        'distance' => '28.349255038655',
                        'latitude' => 0.0001,
                        'longitude' => 0.0001,
                    ],
                ],
                'expected' => [
                    'distance' => '7.8626686663813',
                    'latitude' => -0.0001,
                    'longitude' => 0.0001,
                ],
            ],
        ];
    }
}
