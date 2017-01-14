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

namespace Kubikvest\Resource\Position;

use Pimple\Container;

class CreatorTest extends \PHPUnit_Framework_TestCase
{
    protected $container;

    public function setUp()
    {
        $this->container = $this->createMock(Container::class);

        $this->container->method('offsetGet')->willReturn(
            [
                PositionFormatType::LATITUDE  => 1,
                PositionFormatType::LONGITUDE => 2,
                PositionFormatType::ACCURACY  => 3,
            ]
        );
    }

    public function testInstance()
    {
        $actual = new Creator($this->container);
        $this->assertInstanceOf('\\Kubikvest\\Resource\\Position\\Creator', $actual);
    }

    public function testCreate()
    {
        $creator = new Creator($this->container);
        $actual  = $creator->create();
        $this->assertInstanceOf('\\Kubikvest\\Resource\\Position\\Model\\Position', $actual);
    }
}
