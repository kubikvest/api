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
use Kubikvest\Model\Geo\Sector;
use Kubikvest\Resource\Position\Model\Position;

class PositionAroundBorderSector
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
     * PositionAroundBorderSector constructor.
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
     * Сравнить Accuracy позиции игрока с расстоянием до границы сектора
     *
     * @return bool
     */
    public function validate()
    {
        $distances = $this->calcDistancesToPointsSector($this->position, $this->sector);
        sort($distances);
        /**
         * @var Distance $a
         * @var Distance $b
         */
        list($a, $b) = $distances;
        $distanceAB = (new Distance($a->getTo(), $b->getTo()))->getValue();

        $altitudeTriangle = $this->altitudeTriangle($a->getValue(), $b->getValue(), $distanceAB);

        return $altitudeTriangle >= $this->position->getAccuracy();
    }

    /**
     * Подсчитать расстояние от позиции игрока до каждой точки сектора
     *
     * @param Position $position
     * @param Sector   $sector
     *
     * @return array
     */
    public function calcDistancesToPointsSector(Position $position, Sector $sector)
    {
        $points = [
            [$sector->getLatitudeRange()->getMin(), $sector->getLongitudeRange()->getMin()],
            [$sector->getLatitudeRange()->getMin(), $sector->getLongitudeRange()->getMax()],
            [$sector->getLatitudeRange()->getMax(), $sector->getLongitudeRange()->getMin()],
            [$sector->getLatitudeRange()->getMax(), $sector->getLongitudeRange()->getMax()],
        ];
        $res = [];
        foreach ($points as $point) {
            list($latPoint, $lngPoint) = $point;
            $distance = new Distance($position->getCoordinate(), new Coordinate($latPoint, $lngPoint));
            $res[(string)$distance->getValue()] = $distance;
        }

        return $res;
    }

    /**
     * Вычислить высоту треугольника
     *
     * @param float $a
     * @param float $b
     * @param float $c
     *
     * @return float
     */
    public function altitudeTriangle($a, $b, $c)
    {
        $p = 0.5 * ($a + $b + $c);
        $s = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));

        return 2 * $s / $c;
    }
}
