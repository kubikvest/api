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

class PlayerAtRightPlace
{
    /**
     * @var PositionInsideSector
     */
    private $positionInsideSector;
    /**
     * @var PointIncludedAccuracyRange
     */
    private $pointIncludedAccuracyRange;
    /**
     * @var AccuracyLessDistance
     */
    private $accuracyLessDistance;
    /**
     * @var PositionAroundBorderSector
     */
    private $positionAroundBorderSector;

    public function __construct(
        PositionInsideSector $positionInsideSector,
        PointIncludedAccuracyRange $pointIncludedAccuracyRange,
        AccuracyLessDistance $accuracyLessDistance,
        PositionAroundBorderSector $positionAroundBorderSector
    ) {

        $this->positionInsideSector = $positionInsideSector;
        $this->pointIncludedAccuracyRange = $pointIncludedAccuracyRange;
        $this->accuracyLessDistance = $accuracyLessDistance;
        $this->positionAroundBorderSector = $positionAroundBorderSector;
    }

    public function validate()
    {
        return $this->positionInsideSector->validate() ||
            $this->pointIncludedAccuracyRange->validate() &&
            $this->accuracyLessDistance->validate() ||
            $this->positionAroundBorderSector->validate();
    }
}
