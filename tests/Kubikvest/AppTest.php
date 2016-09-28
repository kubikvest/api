<?php

namespace Kubikvest\Model;

use Kubikvest\Manager\UserManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Psr7\Response;

class AppTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Silex\Application
     */
    protected $app = null;

    public function setUp()
    {
        $this->app = require_once __DIR__.'/../../web/app.php';

        $this->app['curl'] = $this->mockVk();
        $this->app['user.manager'] = $this->mockUser();

        //$this->app['debug'] = true;
        //unset($this->app['exception_handler']);
    }

    public function mockVk()
    {
        $body = json_encode([
            'user_id'      => 66748,
            'access_token' => '533bacf01e11f55b536a565b57531ac114461ae8736d6506a3',
            'expires_in'   => 43200,
        ]);
        $response = new Response(JsonResponse::HTTP_OK, [], $body);
        $mock = $this->createMock('GuzzleHttp\Client');
        $mock->method('request')->willReturn($response);

        return $mock;
    }

    public function mockUser()
    {
        $user = new User();
        $mock = $this->createMock(UserManager::class);
        $mock->method('getUser')->willReturn($user);
        $mock->method('newbie');
        $mock->method('update');

        return $mock;
    }

    public function te1stInstance()
    {
        $actual = $this->app;

        $this->assertInstanceOf('\\Silex\\Application', $actual);
    }

    /**
     * @dataProvider ctrlDataProvider
     */
    public function testCtrl($data, $expected)
    {
        $request  = Request::create($data['uri'], $data['method'], $data['params']);
        $response = $this->app->handle($request);

        $this->assertEquals($expected, $response->getContent());
    }

    public function ctrlDataProvider()
    {
        return [
            [
                'data' => [
                    'uri'    => '/auth',
                    'method' => 'GET',
                    'params' => ['code' => 222],
                ],
                'expected' => '{"links":{"task":"http:\/\/server\/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM"}}',
            ]
        ];
    }
}
