<?php

namespace Kubikvest\Model\Geo;

class DistanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $data
     * @param $expected
     */
    public function testInstance()
    {
        $actual = new Distance(new Coordinate(0, 0), new Coordinate(0, 0));

        $this->assertInstanceOf('\\Kubikvest\\Model\\Geo\\Distance', $actual);
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider getFromDataProvider
     */
    public function testGetFrom($data, $expected)
    {
        $distance = new Distance($data, new Coordinate(0, 0));

        $actual = $distance->getFrom();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @see testGetFrom
     * @return array
     */
    public function getFromDataProvider()
    {
        return [
            [
                'data' => new Coordinate(10, 10),
                'expected' => new Coordinate(10, 10),
            ],
        ];
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider getToDataProvider
     */
    public function testGetTo($data, $expected)
    {
        $distance = new Distance(new Coordinate(0, 0), $data);

        $actual = $distance->getTo();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @see testGetTo
     * @return array
     */
    public function getToDataProvider()
    {
        return [
            [
                'data' => new Coordinate(10, 10),
                'expected' => new Coordinate(10, 10),
            ],
        ];
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider getValueDataProvider
     */
    public function testGetValue($data, $expected)
    {
        $distance = new Distance($data['from'], $data['to']);

        $actual = $distance->getValue();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @see testGetValue
     * @return array
     */
    public function getValueDataProvider()
    {
        return [
            [
                'data' => [
                    'from' => new Coordinate(0, 0),
                    'to' => new Coordinate(0.0001, 0.0001),
                ],
                'expected' => 15.725337332778,
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
        $distance = new Distance($data['from'], $data['to']);

        $actual = $distance->vincentyGreatCircleDistance(
            $distance->getFrom()->getLatitude(),
            $distance->getFrom()->getLongitude(),
            $distance->getTo()->getLatitude(),
            $distance->getTo()->getLongitude()
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
                    'from' => new Coordinate(0, 0),
                    'to' => new Coordinate(0.0001, 0.0001),
                ],
                'expected' => 15.725337332778,
            ],
        ];
    }
}
