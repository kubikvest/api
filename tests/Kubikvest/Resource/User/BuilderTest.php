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
use Kubikvest\Resource\Group\Builder as GroupBuilder;
use Kubikvest\Resource\Group\Model\Group;
use Kubikvest\Resource\User\Model\User;
use Pimple\Container;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new Builder($this->createMock(Container::class));
        $this->assertInstanceOf(Builder::class, $actual);
    }

    /**
     * @param $data
     * @dataProvider buildDataProvider
     */
    public function testBuild($data)
    {
        $mapper = $this->createMock(Mapper::class);
        $mapper->method('getUser')->willReturn($data[User::class]);

        $groupBuilder = $this->createMock(GroupBuilder::class);
        $groupBuilder->method('build')->willReturn(new Group());

        $container = $this->createMock(Container::class);
        $container->expects($this->any())
            ->method('offsetGet')->with(
            $this->logicalOr(
                $this->equalTo(Mapper::class),
                $this->equalTo(GroupBuilder::class)
            )
        )->willReturnCallback(
            function ($value) use ($mapper, $groupBuilder) {
                if ($value == Mapper::class) {
                    return $mapper;
                }

                return $groupBuilder;
            }
        );

        $builder = new Builder($container);
        $actual  = $builder->build($data['uuid']);
        $this->assertInstanceOf(User::class, $actual);
    }

    public function buildDataProvider()
    {
        return [
            [
                'data' => [
                    'uuid'      => new Uuid('5d47df8f-a62b-4bc6-9b0d-8cb1f03523dc'),
                    User::class => [
                        'user_id'      => '5d47df8f-a62b-4bc6-9b0d-8cb1f03523dc',
                        'provider'     => 'vk',
                        'uid'          => '123123',
                        'access_token' => '5d47df8f',
                        'group_id'     => '2b7b8f82-199c-4481-9532-ae557ff9ec8d',
                        'ttl'          => 900,
                    ],
                ],
            ]
        ];
    }
}
