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
use Kubikvest\Resource\User\Builder as UserBuilder;
use Kubikvest\Resource\User\Model\User;
use Pimple\Container;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    protected function setUp()
    {
        parent::setUp();
        $this->container = $this->createMock(Container::class);
    }

    public function testInstance()
    {
        $actual = new Builder($this->container);
        $this->assertInstanceOf(Builder::class, $actual);
    }

    /**
     * @param $groupId
     * @param $expected
     * @dataProvider buildDataProvider
     */
    public function testBuild($groupId, $dataMapper)
    {
        $userBuilder = $this->createMock(UserBuilder::class);
        $userBuilder->method('build')->willReturn(new User());

        $mapper = $this->createMock(Mapper::class);
        $mapper->method('getGroup')->willReturn($dataMapper);

        $this->container->method('offsetGet')->with(
            $this->logicalOr(
                $this->equalTo(UserBuilder::class),
                $this->equalTo(Mapper::class)
            )
        )->willReturnCallback(function ($value) use ($userBuilder, $mapper) {
            if ($value == Mapper::class) {
                return $mapper;
            }

            return $userBuilder;
        });

        $builder = new Builder($this->container);
        $actual = $builder->build($groupId);
        $this->assertInstanceOf(Group::class, $actual);
    }

    /**
     * @see testBuild
     * @return array
     */
    public function buildDataProvider()
    {
        return [
            [
                'groupId' => new Uuid('5d47df8f-a62b-4bc6-9b0d-8cb1f03523dc'),
                'dataMapper' => [
                    'groupId' => '5d47df8f-a62b-4bc6-9b0d-8cb1f03523dc',
                    'gameId'  => '23ecb2d3-2e37-4b55-9f7d-0efc68f83aea',
                    'questId' => '2b7b8f82-199c-4481-9532-ae557ff9ec8d',
                    'pointId' => 'fd8f21a4-a6e4-41d8-90bd-ea4152b57149',
                    'pin'     => 9087,
                    'active'  => 1,
                    'users'   => [
                        'eb052787-94d7-4a27-909e-b62f99ecdee6',
                        'eeff77b9-8b13-4ffd-b161-70d4909032f6',
                    ],
                    'startPoint' => '2017-01-17 09:11:00',
                ],
                'expected' => function () {
                    $group = new Group();
                    $group->setGroupId(new Uuid('5d47df8f-a62b-4bc6-9b0d-8cb1f03523dc'));

                    return $group;
                },
            ],
        ];
    }
}
