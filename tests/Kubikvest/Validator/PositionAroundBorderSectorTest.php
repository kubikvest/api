<?php
/**
 * Copyright (C) 2017. iMega ltd Dmitry Gavriloff (email: info@imega.ru),
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Kubikvest\Validator;

use Kubikvest\Model\Geo\Coordinate;
use Kubikvest\Model\Geo\Distance;
use Kubikvest\Model\Geo\Sector;
use Kubikvest\Resource\Position\Model\Position;

class PositionAroundBorderSectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @param $position
     * @param $sector
     * @param $expected
     *
     * @dataProvider validateDataProvider
     */
    public function testValidate($position, $sector, $expected)
    {
        $actual = (new PositionAroundBorderSector())->validate($position, $sector);
        $this->assertSame($expected, $actual);
    }

    public function validateDataProvider()
    {
        return [
            [
                'position' => new Position(new Coordinate(0.0002, 0), 0),
                'sector'   => new Sector(
                    new Distance(new Coordinate(-0.0001, -0.0001), new Coordinate(0.0001, 0.0001))
                ),
                'expected' => true,
            ],
            [
                'position' => new Position(new Coordinate(0.0002, 0), 12),
                'sector'   => new Sector(
                    new Distance(new Coordinate(-0.0001, -0.0001), new Coordinate(0.0001, 0.0001))
                ),
                'expected' => false,
            ],
        ];
    }

    /**
     * @param array $data
     * @param array $expected
     *
     * @dataProvider calcDistancesToPointsSectorDataProvider
     */
    public function testCalcDistancesToPointsSector($position, $sector, $expected)
    {
        $actual = (new PositionAroundBorderSector())->calcDistancesToPointsSector($position, $sector);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @see testCalcDistancesToPointsSector
     *
     * @return array
     */
    public function calcDistancesToPointsSectorDataProvider()
    {
        return [
            [
                'position' => new Position(new Coordinate(0, 0), 0),
                'sector'   => new Sector(
                    new Distance(new Coordinate(-0.0001, -0.0001), new Coordinate(0.0001, 0.0001))
                ),
                'expected' => [
                    '15.725337332778' => new Distance(new Coordinate(0, 0), new Coordinate(0.0001, 0.0001)),
                ],
            ],
            [
                'position' => new Position(new Coordinate(-0.00015, 0.00015), 0),
                'sector'   => new Sector(
                    new Distance(new Coordinate(-0.0001, -0.0001), new Coordinate(0.0001, 0.0001))
                ),
                'expected' => [
                    '28.34925503859'  => new Distance(
                        new Coordinate(-0.00015, 0.00015), new Coordinate(-0.0001, -0.0001)
                    ),
                    '7.8626686663813' => new Distance(
                        new Coordinate(-0.00015, 0.00015), new Coordinate(-0.0001, 0.0001)
                    ),
                    '39.313343331937' => new Distance(
                        new Coordinate(-0.00015, 0.00015), new Coordinate(0.0001, -0.0001)
                    ),
                    '28.349255038655' => new Distance(
                        new Coordinate(-0.00015, 0.00015), new Coordinate(0.0001, 0.0001)
                    ),
                ],
            ],
        ];
    }

    /**
     * @param array $data
     * @param float $expected
     *
     * @dataProvider altitudeTriangleDataProvider
     */
    public function testAltitudeTriangle($data, $expected)
    {
        $actual = (new PositionAroundBorderSector())->altitudeTriangle($data['a'], $data['b'], $data['c']);
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
                'data'     => [
                    'a' => 30,
                    'b' => 40,
                    'c' => 50,
                ],
                'expected' => 24.0,
            ],
        ];
    }
}
