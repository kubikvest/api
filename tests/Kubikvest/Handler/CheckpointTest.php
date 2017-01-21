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

namespace Kubikvest\Handler;

use Silex\Application;

class CheckpointTest extends \PHPUnit_Framework_TestCase
{
    private $app;

    protected function setUp()
    {
        $this->app = $this->createMock(Application::class);
        parent::setUp();
    }

    public function testInstance()
    {
        $actual = new Checkpoint($this->app);
        $this->assertInstanceOf(Checkpoint::class, $actual);
    }

    /**
     * @param $request
     * @param $expected
     * @dataProvider handleDataProvider
     */
    public function te1stHandle($request, $expected)
    {
        $checkpoint = new Checkpoint($this->app);
        $actual     = $checkpoint->handle($request);
        $this->assertSame($expected, $actual);
    }

    /**
     * @see testHandle
     * @return array
     */
    public function handleDataProvider()
    {
        return [
            [
                'request'  => [],
                'expected' => [],
            ],
        ];
    }
}
