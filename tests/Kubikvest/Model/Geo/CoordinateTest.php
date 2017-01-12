<?php

namespace Kubikvest\Model\Geo;

class CoordinateTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new Coordinate(0, 0);

        $this->assertInstanceOf('\\Kubikvest\\Model\\Geo\\Coordinate', $actual);
    }

    public function testCoords()
    {
        $actual = new Coordinate(10.00009, 0.00009);

        $this->assertSame((float) 10.00009, $actual->getLatitude());
        $this->assertSame((float) 0.00009, $actual->getLongitude());
    }
}
