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

namespace Kubikvest\Resource;

use PHPUnit\Framework\TestCase;

class TokenTest extends TestCase
{
    public function test_getAud_withValidToken_ReturnsStdClass()
    {
        $t      = new Token('mykey', 86000);
        $actual = $t->getAud($t->gen('myaud'));

        $this->assertEquals('myaud', $actual);
    }

    public function test_getAud_withInvalidToken_ReturnsException()
    {
        $t = new Token('mykey', 86000);
        $this->expectException(\UnexpectedValueException::class);
        $t->getAud(null);
    }
}
