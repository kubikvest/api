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

namespace Kubikvest\Resource\Point;

use Kubikvest\Model\Geo\Coordinate;
use Kubikvest\Model\Geo\Distance;
use Kubikvest\Model\Geo\Sector;
use Kubikvest\Model\Prompt;
use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Point\Model\Point;

class PointFormatTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $data
     * @param $expected
     * @dataProvider getValueDataProvider
     */
    public function testGetValue($data, $expected)
    {
        $actual = PointFormatType::getValue($data);

        $this->assertEquals($expected(), $actual);
    }

    public function getValueDataProvider()
    {
        return [
            [
                'data' => [
                    Mapper::POINT_ID => 111,
                    Mapper::TITLE    => 'title',
                    Mapper::DESCRIPTION => 'description',
                    Mapper::COORDS => [
                        Coordinate::LATITUDE => [1, 3],
                        Coordinate::LONGITUDE => [2, 4],
                    ],
                    Mapper::PROMPT => [
                        10 => 'text',
                    ],
                ],
                'expected' => function () {
                    $point = new Point();
                    $point->setPointId(new Uuid(111));
                    $point->setTitle('title');
                    $point->setDescription('description');
                    $point->setSector(new Sector(
                        new Distance(new Coordinate(1, 2), new Coordinate(3, 4))
                    ));
                    $data = [];
                    $prompt = [
                        10 => 'text',
                    ];
                    foreach ($prompt as $delay => $text) {
                        $prompt = new Prompt();
                        $prompt->setDelay($delay);
                        $prompt->setText($text);
                        $data[] = $prompt;
                    }
                    $point->setPrompt($data);

                    return $point;
                },
            ],
        ];
    }
}
