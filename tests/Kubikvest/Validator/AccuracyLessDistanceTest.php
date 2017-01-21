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

class AccuracyLessDistanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $position
     * @param $sector
     * @param $expected
     * @dataProvider validateDataProvider
     */
    public function testValidate($position, $sector, $expected)
    {
        $actual = (new AccuracyLessDistance($position, $sector))->validate();
        $this->assertSame($expected, $actual);
    }

    public function validateDataProvider()
    {
        return [
            [
                'position' => new Position(new Coordinate(60.170449, 24.943343), 21),
                'sector'   => new Sector(new Distance(new Coordinate(60.170267, 24.943354), new Coordinate(60.169866, 24.944738))),
                'expected' => true,
            ],
            [
                'position' => new Position(new Coordinate(60.170449, 24.943343), 20),
                'sector'   => new Sector(new Distance(new Coordinate(60.170267, 24.943354), new Coordinate(60.169866, 24.944738))),
                'expected' => false,
            ],
            [
                'position' => new Position(new Coordinate(60.983729, 25.659049), 11),
                'sector'   => new Sector(
                    new Distance(new Coordinate(60.983826, 25.658975), new Coordinate(60.983902, 25.659115))
                ),
                'expected' => false,
            ],
            [
                'position' => new Position(new Coordinate(60.983729, 25.659049), 12),
                'sector'   => new Sector(
                    new Distance(new Coordinate(60.983826, 25.658975), new Coordinate(60.983902, 25.659115))
                ),
                'expected' => true,
            ],
        ];
    }
}
