<?php

namespace Kubikvest\Model;

class PointTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new Point();

        $this->assertInstanceOf('\\Kubikvest\\Model\\Point', $actual);
    }
}
