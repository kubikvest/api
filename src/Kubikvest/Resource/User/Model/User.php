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

namespace Kubikvest\Resource\User\Model;

use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Group\Model\Group;
use Kubikvest\Resource\Provider\Model\Provider;

class User
{
    /**
     * @var Uuid
     */
    protected $userId = null;

    /**
     * @var Provider
     */
    protected $provider = null;

    /**
     * @var Group
     */
    protected $group = null;

    /**
     * @return Uuid
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param Uuid $userId
     */
    public function setUserId(Uuid $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return Group
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param Group $group
     */
    public function setGroup(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @return Provider
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param Provider $provider
     */
    public function setProvider(Provider $provider)
    {
        $this->provider = $provider;
    }
}
