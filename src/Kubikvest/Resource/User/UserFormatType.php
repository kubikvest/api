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

use iMega\Formatter\GenericType;
use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Provider\Model\Provider;
use Kubikvest\Resource\User\Model\User;

class UserFormatType extends GenericType
{
    public static function getData($value)
    {
    }

    public static function getValue($value)
    {
        $provider              = new Provider();
        $provider->name        = $value['provider'];
        $provider->uid         = $value['uid'];
        $provider->accessToken = $value['access_token'];
        $provider->ttl         = $value['ttl'];

        $user = new User();
        $user->setUserId(new Uuid($value['user_id']));
        $user->setProvider($provider);
        $user->setGroup($value['group']);

        return $user;
    }
}
