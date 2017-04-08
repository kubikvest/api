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

use Kubikvest\Resource\Position\Model\Position;
use PHPUnit\Framework\TestCase;
use Pimple\Container;

class CreatorTest extends TestCase
{
    public function test_create_WithValidRequest_ReturnsPosition()
    {
        $c = new Creator(
            new Container(
                [
                    'request.content' => [
                        'lat' => 10,
                        'lng' => 20,
                        'acr' => 30,
                    ],
                ]
            )
        );

        $actual = $c->create();

        $this->assertInstanceOf(Position::class, $actual);
    }

    public function test_create_WithValidRequest_ReturnsLatitude10()
    {
        $c = new Creator(
            new Container(
                [
                    'request.content' => [
                        'lat' => 10.0,
                        'lng' => 20.0,
                        'acr' => 30,
                    ],
                ]
            )
        );

        $actual = $c->create();

        $this->assertSame(10.0, $actual->getCoordinate()->getLatitude());
    }

    public function test_create_WithValidRequest_ReturnsLongitude20()
    {
        $c = new Creator(
            new Container(
                [
                    'request.content' => [
                        'lat' => 10.0,
                        'lng' => 20.0,
                        'acr' => 30,
                    ],
                ]
            )
        );

        $actual = $c->create();

        $this->assertSame(20.0, $actual->getCoordinate()->getLongitude());
    }

    public function test_create_WithValidRequest_ReturnsAccuracy30()
    {
        $c = new Creator(
            new Container(
                [
                    'request.content' => [
                        'lat' => 10.0,
                        'lng' => 20.0,
                        'acr' => 30,
                    ],
                ]
            )
        );

        $actual = $c->create();

        $this->assertSame(30, $actual->getAccuracy());
    }
}
