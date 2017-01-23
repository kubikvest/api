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

class PlayerAtRightPlaceTest extends \PHPUnit_Framework_TestCase
{
    private $positionInsideSector;
    private $pointIncludedAccuracyRange;
    private $accuracyLessDistance;
    private $positionAroundBorderSector;

    protected function setUp()
    {
        parent::setUp();
        $this->positionInsideSector       = $this->createMock(PositionInsideSector::class);
        $this->pointIncludedAccuracyRange = $this->createMock(PointIncludedAccuracyRange::class);
        $this->accuracyLessDistance       = $this->createMock(AccuracyLessDistance::class);
        $this->positionAroundBorderSector = $this->createMock(PositionAroundBorderSector::class);
    }

    /**
     * @param $data
     * @param $expected

     * @dataProvider testValidateDataProvider
     */
    public function testValidate($data, $expected)
    {
        $this->positionInsideSector->method('validate')->willReturn($data[PositionInsideSector::class]);
        $this->pointIncludedAccuracyRange->method('validate')->willReturn($data[PointIncludedAccuracyRange::class]);
        $this->accuracyLessDistance->method('validate')->willReturn($data[AccuracyLessDistance::class]);
        $this->positionAroundBorderSector->method('validate')->willReturn($data[PositionAroundBorderSector::class]);

        $actual = (new PlayerAtRightPlace(
            $this->positionInsideSector,
            $this->pointIncludedAccuracyRange,
            $this->accuracyLessDistance,
            $this->positionAroundBorderSector
        ))->validate();
        $this->assertSame($expected, $actual);
    }

    public function testValidateDataProvider()
    {
        return [
            [
                'data'     => [
                    PositionInsideSector::class       => true,
                    PointIncludedAccuracyRange::class => false,
                    AccuracyLessDistance::class       => false,
                    PositionAroundBorderSector::class => false,
                ],
                'expected' => true,
            ],
            [
                'data'     => [
                    PositionInsideSector::class       => false,
                    PointIncludedAccuracyRange::class => true,
                    AccuracyLessDistance::class       => true,
                    PositionAroundBorderSector::class => false,
                ],
                'expected' => true,
            ],
            [
                'data'     => [
                    PositionInsideSector::class       => false,
                    PointIncludedAccuracyRange::class => true,
                    AccuracyLessDistance::class       => true,
                    PositionAroundBorderSector::class => true,
                ],
                'expected' => true,
            ],
            [
                'data'     => [
                    PositionInsideSector::class       => false,
                    PointIncludedAccuracyRange::class => true,
                    AccuracyLessDistance::class       => false,
                    PositionAroundBorderSector::class => true,
                ],
                'expected' => true,
            ],
            [
                'data'     => [
                    PositionInsideSector::class       => false,
                    PointIncludedAccuracyRange::class => false,
                    AccuracyLessDistance::class       => true,
                    PositionAroundBorderSector::class => false,
                ],
                'expected' => false,
            ],
            [
                'data'     => [
                    PositionInsideSector::class       => false,
                    PointIncludedAccuracyRange::class => false,
                    AccuracyLessDistance::class       => false,
                    PositionAroundBorderSector::class => true,
                ],
                'expected' => false,
            ],
            [
                'data'     => [
                    PositionInsideSector::class       => false,
                    PointIncludedAccuracyRange::class => true,
                    AccuracyLessDistance::class       => false,
                    PositionAroundBorderSector::class => false,
                ],
                'expected' => false,
            ],
            [
                'data'     => [
                    PositionInsideSector::class       => false,
                    PointIncludedAccuracyRange::class => false,
                    AccuracyLessDistance::class       => false,
                    PositionAroundBorderSector::class => false,
                ],
                'expected' => false,
            ],
        ];
    }
}
