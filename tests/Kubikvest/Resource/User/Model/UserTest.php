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

class UserTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new User();
        $this->assertInstanceOf(User::class, $actual);
    }

    /**
     * @param $data
     * @dataProvider modelDataProvider
     */
    public function testModel($data)
    {
        $user = new User();
        $user->setUserId($data['userId']);
        $user->setProvider($data['provider']);
        $user->setGroup($data['group']);

        $this->assertSame($data['userId'], $user->getUserId());
        $this->assertSame($data['provider'], $user->getProvider());
        $this->assertSame($data['group'], $user->getGroup());
    }

    public function modelDataProvider()
    {
        return [
            [
                'data' => [
                    'userId' => new Uuid('23ecb2d3-2e37-4b55-9f7d-0efc68f83aea'),
                    'provider' => new Provider(),
                    'group' => new Group(),
                ],
            ],
        ];
    }
}
