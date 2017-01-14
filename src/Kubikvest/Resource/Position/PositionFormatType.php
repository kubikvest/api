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
use iMega\Formatter\GenericType;
use Kubikvest\Model\Geo\Coordinate;
use Kubikvest\Resource\Position\Model\Position;

class PositionFormatType extends GenericType
{
    const LATITUDE = 'lat';
    const LONGITUDE = 'lng';
    const ACCURACY = 'acr';

    public static function getData($value)
    {
        throw new FormatterException('Disable');
    }

    public static function getValue($value)
    {
        if (!isset($value[self::LATITUDE]) || !isset($value[self::LONGITUDE]) || !isset($value[self::ACCURACY])) {
            throw new FormatterException('Empty position');
        }

        return new Position(
            new Coordinate($value[self::LATITUDE], $value[self::LONGITUDE]), $value[self::ACCURACY]
        );
    }
}
