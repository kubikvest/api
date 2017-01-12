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

class RangeTest extends \PHPUnit_Framework_TestCase
{
    const FIRST = 'first';
    const SECOND = 'second';

    public function testInstance()
    {
        $actual = new Range(0, 0);

        $this->assertInstanceOf('\\Kubikvest\\Model\\Geo\\Range', $actual);
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider getMinDataProvider
     */
    public function testGetMin($data, $expected)
    {
        $range = new Range($data[self::FIRST], $data[self::SECOND]);

        $actual = $range->getMin();

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testGetMin
     * @return array
     */
    public function getMinDataProvider()
    {
        return [
            [
                'data' => [
                    self::FIRST  => 1,
                    self::SECOND => 0.00009,
                ],
                'expected' => 0.00009,
            ],
            [
                'data' => [
                    self::FIRST  => 0.001,
                    self::SECOND => 2,
                ],
                'expected' => 0.001,
            ],
        ];
    }

    /**
     * @param $data
     * @param $expected
     * @dataProvider getMaxDataProvider
     */
    public function testGetMax($data, $expected)
    {
        $range = new Range($data[self::FIRST], $data[self::SECOND]);

        $actual = $range->getMax();

        $this->assertSame($expected, $actual);
    }

    /**
     * @see testGetMax
     * @return array
     */
    public function getMaxDataProvider()
    {
        return [
            [
                'data' => [
                    self::FIRST  => 1,
                    self::SECOND => 0.00009,
                ],
                'expected' => 1,
            ],
            [
                'data' => [
                    self::FIRST  => 0.001,
                    self::SECOND => 2,
                ],
                'expected' => 2,
            ],
        ];
    }
}
