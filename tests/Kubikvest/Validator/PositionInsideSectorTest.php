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

class PositionInsideSectorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $position
     * @param $sector
     * @param $expected
     * @dataProvider validateDataProvider
     */
    public function testValidate($position, $sector, $expected)
    {
        $actual = (new PositionInsideSector())->validate($position, $sector);

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testValidate
     * @return array
     */
    public function validateDataProvider()
    {
        return [
            [
                'position' => new Position(new Coordinate(60.983826, 25.658975), 0),
                'sector'   => new Sector(
                    new Distance(
                        new Coordinate(60.983826, 25.658975),
                        new Coordinate(60.983902, 25.659115)
                    )
                ),
                'expected' => true,
            ],
            [
                'position' => new Position(new Coordinate(60.983850, 25.6590), 0),
                'sector'   => new Sector(
                    new Distance(
                        new Coordinate(60.983826, 25.658975),
                        new Coordinate(60.983902, 25.659115)
                    )
                ),
                'expected' => true,
            ],
            [
                'position' => new Position(new Coordinate(60.983826, 25.658974), 0),
                'sector'   => new Sector(
                    new Distance(
                        new Coordinate(60.983826, 25.658975),
                        new Coordinate(60.983902, 25.659115)
                    )
                ),
                'expected' => false,
            ],
            [
                'position' => new Position(new Coordinate(60.983825, 25.658975), 0),
                'sector'   => new Sector(
                    new Distance(
                        new Coordinate(60.983826, 25.658975),
                        new Coordinate(60.983902, 25.659115)
                    )
                ),
                'expected' => false,
            ],
        ];
    }
}
