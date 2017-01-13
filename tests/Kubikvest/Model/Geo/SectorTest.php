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

namespace Kubikvest\Model\Geo;

class SectorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new Sector(
            new Distance(
                new Coordinate(0,0),
                new Coordinate(0,0)
            )
        );

        $this->assertInstanceOf('\\Kubikvest\\Model\\Geo\\Sector', $actual);
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider getLatitudeRangeDataProvider
     */
    public function testGetLatitudeRange($data, $expected)
    {
        $sector = new Sector($data);

        $actual = $sector->getLatitudeRange();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @see testGetLatitudeRange
     * @return array
     */
    public function getLatitudeRangeDataProvider()
    {
        return [
            [
                'data' => new Distance(new Coordinate(58.5422, 31.2260), new Coordinate(58.5426, 31.2271)),
                'expected' => new Range(58.5422, 58.5426),
            ],
        ];
    }
    /**
     * @param $data
     * @param $expected
     * @dataProvider getLongitudeRangeDataProvider
     */
    public function testGetLongitudeRange($data, $expected)
    {
        $sector = new Sector($data);

        $actual = $sector->getLongitudeRange();

        $this->assertEquals($expected, $actual);
    }

    /**
     * @see testGetLongitudeRange
     * @return array
     */
    public function getLongitudeRangeDataProvider()
    {
        return [
            [
                'data' => new Distance(new Coordinate(58.5422, 31.2260), new Coordinate(58.5426, 31.2271)),
                'expected' => new Range(31.2260, 31.2271),
            ],
        ];
    }
}
