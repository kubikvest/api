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

namespace Kubikvest\Resource\Position;

use iMega\Formatter\FormatterException;
use Kubikvest\Model\Geo\Coordinate;
use Kubikvest\Resource\Position\Model\Position;

class PositionFormatTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $data
     * @param $expected
     *
     * @dataProvider getDataDataProvider
     */
    public function testGetData($data, $expected)
    {
        try {
            PositionFormatType::getData($data);
        } catch (FormatterException $e) {
            $this->assertEquals($expected, $e);
        }
    }

    /**
     * @see testGetData
     * @return array
     */
    public function getDataDataProvider()
    {
        return [
            [
                'data'     => '',
                'expected' => new FormatterException('Disable'),
            ],
        ];
    }

    /**
     * @param $data
     * @param $expected
     *
     * @dataProvider getValueDataProvider
     */
    public function testGetValue($data, $expected)
    {
        try {
            $actual = PositionFormatType::getValue($data);
            $this->assertEquals($expected, $actual);
        } catch (FormatterException $e) {
            $this->assertEquals($expected, $e);
        }
    }

    /**
     * @see testGetValue
     * @return array
     */
    public function getValueDataProvider()
    {
        return [
            [
                'data'     => [
                    PositionFormatType::LATITUDE  => 1,
                    PositionFormatType::LONGITUDE => 2,
                    PositionFormatType::ACCURACY  => 10,
                ],
                'expected' => new Position(new Coordinate(1, 2), 10),
            ],
            [
                'data'     => [
                    PositionFormatType::LONGITUDE => 2,
                    PositionFormatType::ACCURACY  => 10,
                ],
                'expected' => new FormatterException('Empty position'),
            ],
            [
                'data'     => [
                    PositionFormatType::LATITUDE  => 1,
                    PositionFormatType::ACCURACY  => 10,
                ],
                'expected' => new FormatterException('Empty position'),
            ],
            [
                'data'     => [
                    PositionFormatType::LATITUDE  => 1,
                    PositionFormatType::LONGITUDE => 2,
                ],
                'expected' => new FormatterException('Empty position'),
            ],
            [
                'data'     => [
                    PositionFormatType::LATITUDE,
                    PositionFormatType::LONGITUDE,
                    PositionFormatType::LONGITUDE,
                ],
                'expected' => new FormatterException('Empty position'),
            ],
            [
                'data'     => [],
                'expected' => new FormatterException('Empty position'),
            ],
        ];
    }
}
