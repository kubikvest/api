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

namespace Kubikvest\Resource\Group;

use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Point\Model\Point;
use Kubikvest\Resource\Quest\Model\Quest;

class NextPoint
{
    /**
     * @param Quest $quest
     * @param Point $point
     *
     * @return Uuid
     */
    public function nextPoint(Quest $quest, Point $point)
    {
        $index = array_keys($quest->points, $point->getPointId()->getValue())[0];
        if ($index == count($quest->points) - 1) {
            return new Uuid(end($quest->points));
        } else {
            return new Uuid($quest->points[$index + 1]);
        }
    }
}
