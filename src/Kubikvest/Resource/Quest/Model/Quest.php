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

namespace Kubikvest\Resource\Quest\Model;

use Kubikvest\Model\Uuid;

class Quest
{
    /**
     * @var Uuid
     */
    protected $questId = null;
    public $title = null;
    public $description = null;
    public $picture = null;
    public $points = [];

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->questId;
    }

    /**
     * @param string $currentPoint
     *
     * @return string
     */
    public function nextPoint($currentPoint)
    {
        $index = array_keys($this->points, $currentPoint)[0];
        if ($index == count($this->points) - 1) {
            return end($this->points);
        } else {
            return $this->points[$index + 1];
        }
    }

    /**
     * @return Uuid
     */
    public function getQuestId()
    {
        return $this->questId;
    }

    /**
     * @param Uuid $questId
     */
    public function setQuestId(Uuid $questId)
    {
        $this->questId = $questId;
    }
}
