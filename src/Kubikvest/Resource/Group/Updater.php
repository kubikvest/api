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

use Kubikvest\Resource\Group\Model\Group;
use Pimple\Container;

class Updater
{
    /**
     * @var Container
     */
    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @return Group
     */
    public function update(Group $group)
    {
        /**
         * @var Mapper $mapper
         */
        $mapper = $this->container[Mapper::class];

        $users = [];
        foreach ($group->getUsers() as $user) {
            $users[] = $user->getUserId()->getValue();
        }

        $mapper->update(
            [
                'groupId'    => $group->getGroupId()->getValue(),
                'gameId'     => $group->getGameId()->getValue(),
                'questId'    => $group->getQuestId()->getValue(),
                'pointId'    => $group->getPointId()->getValue(),
                'users'      => $users,
                'pin'        => $group->pin,
                'startPoint' => $group->getStartPoint(),
                'active'     => $group->active,
            ]
        );
    }
}
