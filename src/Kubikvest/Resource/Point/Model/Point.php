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

namespace Kubikvest\Resource\Point\Model;

use Kubikvest\Model\Geo\Sector;
use Kubikvest\Model\Uuid;

class Point
{
    /**
     * @var Uuid
     */
    protected $pointId = null;
    protected $title = null;
    protected $description = null;
    protected $sector = null;
    /**
     * @var array
     */
    protected $prompt = null;

    /**
     * @return Uuid
     */
    public function getPointId()
    {
        return $this->pointId;
    }

    /**
     * @param Uuid $pointId
     */
    public function setPointId($pointId)
    {
        $this->pointId = $pointId;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return Sector
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * @param Sector $sector
     */
    public function setSector($sector)
    {
        $this->sector = $sector;
    }

    /**
     * @return array
     */
    public function getPrompt()
    {
        return $this->prompt;
    }

    /**
     * @param array $prompt
     */
    public function setPrompt($prompt)
    {
        $this->prompt = $prompt;
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->pointId;
    }
}
