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

use Kubikvest\Model\Geo\Distance;
use Kubikvest\Model\Geo\Sector;
use Kubikvest\Resource\Position\Model\Position;

class AccuracyLessDistance
{
    /**
     * @var Position
     */
    private $position;
    /**
     * @var Sector
     */
    private $sector;

    /**
     * AccuracyLessDistance constructor.
     *
     * @param Position $position
     * @param Sector   $sector
     */
    public function __construct(Position $position, Sector $sector)
    {
        $this->position = $position;
        $this->sector = $sector;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        return $this->nearestPoint($this->position, $this->sector)->getValue() <= $this->position->getAccuracy();
    }

    /**
     * Ближайшая точка до сектора
     *
     * @param Position $position
     * @param Sector   $sector
     *
     * @return Distance
     */
    public function nearestPoint(Position $position, Sector $sector)
    {
        $distances = (new PositionAroundBorderSector($position, $sector))
            ->calcDistancesToPointsSector($position, $sector);

        return $distances[min(array_keys($distances))];
    }
}
