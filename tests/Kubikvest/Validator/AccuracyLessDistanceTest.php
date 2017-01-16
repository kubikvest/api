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
use Kubikvest\Resource\Position\Model\Position;

class AccuracyLessDistanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $position
     * @param $distance
     * @param $expected
     * @dataProvider validateDataProvider
     */
    public function testValidate($position, $distance, $expected)
    {
        $actual = (new AccuracyLessDistance($position, $distance))->validate();
        $this->assertSame($expected, $actual);
    }

    public function validateDataProvider()
    {
        return [
            [
                'position' => new Position(new Coordinate(0.00012, 0.00012), 15),
                'distance' => new Distance(new Coordinate(0, 0), new Coordinate(0.0001, 0.0001)),
                'expected' => true,
            ],
            [
                'position' => new Position(new Coordinate(0.00012, 0.00012), 16),
                'distance' => new Distance(new Coordinate(0, 0), new Coordinate(0.0001, 0.0001)),
                'expected' => false,
            ],
        ];
    }
}
