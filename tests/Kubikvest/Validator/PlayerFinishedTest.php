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

namespace Kubikvest\Validator;

use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Group\Model\Group;
use Kubikvest\Resource\Quest\Model\Quest;

class PlayerFinishedTest extends \PHPUnit_Framework_TestCase
{
    private $group;
    private $quest;

    protected function setUp()
    {
        parent::setUp();
        $this->group = $this->createMock(Group::class);
        $this->quest = $this->createMock(Quest::class);
    }

    public function testInstance()
    {
        $actual = new PlayerFinished($this->group, $this->quest);
        $this->assertInstanceOf(PlayerFinished::class, $actual);
    }

    /**
     * @param $group
     * @param $quest
     * @param $expected
     *
     * @dataProvider validateDataProvider
     */
    public function testValidate($group, $quest, $expected)
    {
        $validator = new PlayerFinished($group(), $quest());
        $actual    = $validator->validate();
        $this->assertSame($expected, $actual);
    }

    public function validateDataProvider()
    {
        return [
            [
                'group'    => function () {
                    $group = new Group();
                    $group->setPointId(new Uuid('7c12bdb0-b1d5-4f8b-867c-2572a712c3f9'));

                    return $group;
                },
                'quest'    => function () {
                    $quest         = new Quest();
                    $quest->points = [
                        '84e4c4e4-c27d-465a-8155-cf36fbff1536',
                        '7c12bdb0-b1d5-4f8b-867c-2572a712c3f9',
                    ];

                    return $quest;
                },
                'expected' => true,
            ],
            [
                'group'    => function () {
                    $group = new Group();
                    $group->setPointId(new Uuid('7c12bdb0-b1d5-4f8b-867c-2572a712c3f9'));

                    return $group;
                },
                'quest'    => function () {
                    $quest         = new Quest();
                    $quest->points = [
                        '7c12bdb0-b1d5-4f8b-867c-2572a712c3f9',
                        '84e4c4e4-c27d-465a-8155-cf36fbff1536',
                    ];

                    return $quest;
                },
                'expected' => false,
            ],
        ];
    }
}
