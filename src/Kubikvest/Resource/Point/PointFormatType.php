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

use iMega\Formatter\FormatterException;
use iMega\Formatter\GenericType;
use Kubikvest\Model\Geo\Coordinate;
use Kubikvest\Model\Geo\Distance;
use Kubikvest\Model\Geo\Sector;
use Kubikvest\Model\Prompt;
use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Point\Model\Point;

class PointFormatType extends GenericType
{
    public static function getData($value)
    {
        throw new FormatterException('Disable');
    }

    public static function getValue($value)
    {
        $point = new Point();
        $point->setPointId(new Uuid($value[Mapper::POINT_ID]));
        $point->setTitle($value[Mapper::TITLE]);
        $point->setDescription($value[Mapper::DESCRIPTION]);
        $point->setSector(new Sector(
            new Distance(
                new Coordinate(
                    $value[Mapper::COORDS][Coordinate::LATITUDE][0],
                    $value[Mapper::COORDS][Coordinate::LONGITUDE][0]
                ),
                new Coordinate(
                    $value[Mapper::COORDS][Coordinate::LATITUDE][1],
                    $value[Mapper::COORDS][Coordinate::LONGITUDE][1]
                )
            )
        ));
        $data = [];
        foreach ($value[Mapper::PROMPT] as $delay => $text) {
            $prompt = new Prompt();
            $prompt->setDelay($delay);
            $prompt->setText($text);
            $data[] = $prompt;
        }
        $point->setPrompt($data);

        return $point;
    }
}
