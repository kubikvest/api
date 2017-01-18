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

use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Provider\Model\Provider;
use Kubikvest\Resource\User\Model\User;
use Pimple\Container;
use Kubikvest\Resource\Group\Builder as GroupBuilder;

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
     * @param Uuid $uuid
     *
     * @return User
     */
    public function build(Uuid $uuid)
    {
        /**
         * @var Mapper $mapper
         */
        $mapper = $this->container[Mapper::class];
        $data   = $mapper->getUser($uuid->getValue());

        $provider        = new Provider();
        $provider->name  = $data['provider'];
        $provider->uid   = $data['uid'];
        $provider->token = $data['access_token'];
        $provider->ttl   = $data['ttl'];

        $user = new User();
        $user->setUserId(new Uuid($data['user_id']));
        $user->setProvider($provider);
        /**
         * @var GroupBuilder $groupBuilder
         */
        $groupBuilder = $this->container[GroupBuilder::class];
        $user->setGroup($groupBuilder->build(new Uuid($data['group_id'])));

        return $user;
    }
}
