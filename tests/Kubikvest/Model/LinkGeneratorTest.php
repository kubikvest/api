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
     * @dataProvider testGetLinkDataProvider
     */
    public function testGetLink($data, $expected)
    {
        $user = $data['user'];
        $linkGen = new LinkGenerator($data['url'], $data['key']);
        $actual = $linkGen->getLink($data['type'], $user(), $data['ttl']);

        $this->assertEquals($expected, $actual);
    }

    public function testGetLinkDataProvider()
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
                        $user->userId   = 66748;
                        $user->provider = 'vk';
                        $user->questId  = 'd9b135d3-9a29-45f0-8742-7ca6f99d9b73';
                        $user->pointId  = '16a4f9df-e636-4cfc-ae32-910c0a20ba03';

                        return $user;
                    },
                ],
                'expected' => 'http://kubikvest.xyz/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM',
            ],
        ];
    }
}
