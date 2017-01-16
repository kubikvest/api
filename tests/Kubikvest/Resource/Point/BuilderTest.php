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

namespace Kubikvest\Resource\Point;

use Kubikvest\Model\Geo\Coordinate;
use Kubikvest\Model\Uuid;
use Kubikvest\Resource\Point\Model\Point;

class BuilderTest extends \PHPUnit_Framework_TestCase
{
    private $mapper;

    protected function setUp()
    {
        parent::setUp();
        $this->mapper = $this->createMock(Mapper::class);
        $this->mapper->method('getPoint')->willReturn(
            [
                Mapper::POINT_ID => 111,
                Mapper::TITLE    => 'title',
                Mapper::DESCRIPTION => 'description',
                Mapper::COORDS => [
                    Coordinate::LATITUDE => [1, 3],
                    Coordinate::LONGITUDE => [2, 4],
                ],
                Mapper::PROMPT => [
                    10 => 'text',
                ],
            ]
        );
    }

    public function testInstance()
    {
        $actual = new Builder($this->mapper);
        $this->assertInstanceOf(Builder::class, $actual);
    }

    public function testBuild()
    {
        $builder = new Builder($this->mapper);
        $actual = $builder->build(new Uuid(111));
        $this->assertInstanceOf(Point::class, $actual);
    }
}
