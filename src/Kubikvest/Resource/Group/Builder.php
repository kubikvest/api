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
use Kubikvest\Resource\Group\Model\Group;
use Pimple\Container;
use Kubikvest\Resource\User\Builder as UserBuilder;

class Builder
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
     * @return Model\Group
     */
    public function build(Uuid $uuid)
    {
        /**
         * @var Mapper $mapper
         */
        $mapper = $this->container[Mapper::class];
        $data = $mapper->getGroup($uuid->getValue());

        $group = new Group();
        $group->setGroupId(new Uuid($data['groupId']));
        $group->setGameId(new Uuid($data['gameId']));
        $group->setQuestId(new Uuid($data['questId']));
        $group->setPointId(new Uuid($data['pointId']));
        $group->pin = $data['pin'];
        $group->setStartPoint(\DateTime::createFromFormat('Y-m-d H:i:s', $data['startPoint']));
        $group->active = $data['active'];
        foreach ($data['users'] as $user) {
            $group->addUser($this->container[UserBuilder::class]->build(new Uuid($user)));
        }

        return $group;
    }
}
