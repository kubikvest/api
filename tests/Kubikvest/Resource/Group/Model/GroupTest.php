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

class GroupTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new Group();
        $this->assertInstanceOf(Group::class, $actual);
    }

    /**
     * @param $data
     * @dataProvider modelDataProvider
     */
    public function testModel($data)
    {
        $group = new Group();
        $group->setGroupId($data['groupId']);
        $group->setGameId($data['gameId']);
        $group->setQuestId($data['questId']);
        $group->setPointId($data['pointId']);
        $group->setStartPoint($data['startPoint']);
        foreach ($data['users'] as $user) {
            $group->addUser($user);
        }

        $this->assertEquals($data['groupId'], $group->getGroupId());
        $this->assertEquals($data['gameId'], $group->getGameId());
        $this->assertEquals($data['questId'], $group->getQuestId());
        $this->assertEquals($data['pointId'], $group->getPointId());
        $this->assertEquals($data['startPoint'], $group->getStartPoint());
        $this->assertInternalType('array', $group->getUsers());
    }

    public function modelDataProvider()
    {
        return [
            [
                'data' => [
                    'groupId'    => new Uuid('78b553bc-fb23-4442-9644-9d0bb308e325'),
                    'gameId'     => new Uuid('23ecb2d3-2e37-4b55-9f7d-0efc68f83aea'),
                    'questId'    => new Uuid('2b7b8f82-199c-4481-9532-ae557ff9ec8d'),
                    'pointId'    => new Uuid('fd8f21a4-a6e4-41d8-90bd-ea4152b57149'),
                    'startPoint' => date_create_from_format('Y-m-d H:i:s', '2017-01-17 09:11:00'),
                    'users' => [
                        new User(),
                    ],
                ],
            ],
        ];
    }
}
