<?php

namespace Kubikvest\Model;

class LinkGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstance()
    {
        $actual = new LinkGenerator('', '');

        $this->assertInstanceOf('\\Kubikvest\\Model\\LinkGenerator', $actual);
    }

    /**
     * @dataProvider getLinkDataProvider
     */
    public function testGetLink($data, $expected)
    {
        $user = $data['user'];
        $linkGen = new LinkGenerator($data['url'], $data['key']);
        $actual = $linkGen->getLink($data['type'], $user(), $data['ttl']);

        $this->assertEquals($expected, $actual);
    }

    public function getLinkDataProvider()
    {
        return [
            [
                'data' => [
                    'type' => 'task',
                    'url' => 'http://kubikvest.xyz',
                    'key' => 'qwerty',
                    'ttl' => 43200,
                    'user' => function() {
                        $user = new User();
                        $user->userId   = 'adff5c92-008c-47ac-bad8-11be43ea1469';
                        $user->provider = 'vk';
                        $user->uid      = 66748;
                        $user->ttl      = 43200;

                        return $user;
                    },
                ],
                'expected' => 'http://kubikvest.xyz/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiYWRmZjVjOTItMDA4Yy00N2FjLWJhZDgtMTFiZTQzZWExNDY5In0.kRthgyyUgQF3Aa3WwN2NhFnxSQh1OXoXkXiG0G_fX4s',
            ],
        ];
    }
}
