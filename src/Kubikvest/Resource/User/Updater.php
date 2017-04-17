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

namespace Kubikvest\Resource\User;

use Kubikvest\Resource\User\Model\User;
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
     * @param User $user
     */
    public function update(User $user)
    {
        /**
         * @var Mapper $mapper
         */
        $mapper = $this->container[Mapper::class];

        $mapper->update(
            [
                'user_id'      => $user->getUserId()->getValue(),
                'access_token' => $user->getProvider()->token,
                'group_id'     => $user->getGroupId()->getValue(),
                'ttl'          => $user->getProvider()->ttl,
                'start_task'   => '',
            ]
        );
    }
}
