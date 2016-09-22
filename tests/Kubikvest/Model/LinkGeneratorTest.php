<?php

namespace Kubikvest\Model;

class LinkGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new LinkGenerator('', '');
        $this->assertInstanceOf('\\Kubikvest\\Model\\LinkGenerator', $actual);
    }
}
