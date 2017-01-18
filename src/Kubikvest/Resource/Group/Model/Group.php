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

namespace Kubikvest\Resource\Group\Model;

use Kubikvest\Model\Uuid;
use Kubikvest\Resource\User\Model\User;

class Group
{
    /**
     * @var Uuid
     */
    protected $groupId = null;

    /**
     * @var Uuid
     */
    protected $gameId  = null;

    /**
     * @var Uuid
     */
    public $questId = null;

    /**
     * @var Uuid
     */
    public $pointId = null;

    /**
     * @var User[]
     */
    protected $users = [];

    /**
     * @var string
     */
    public $pin = '';

    /**
     * @var \DateTime
     */
    protected $startPoint = null;

    /**
     * @var bool
     */
    public $active = true;

    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user)
    {
        array_push($this->users, $user);
    }

    /**
     * @return Uuid
     */
    public function getGroupId()
    {
        return $this->groupId;
    }

    /**
     * @param Uuid $groupId
     */
    public function setGroupId(Uuid $groupId)
    {
        $this->groupId = $groupId;
    }

    /**
     * @return Uuid
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @param Uuid $gameId
     */
    public function setGameId(Uuid $gameId)
    {
        $this->gameId = $gameId;
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
    public function setPointId(Uuid $pointId)
    {
        $this->pointId = $pointId;
    }

    /**
     * @return \DateTime
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * @param \DateTime $startPoint
     */
    public function setStartPoint(\DateTime $startPoint)
    {
        $this->startPoint = $startPoint;
    }
}
