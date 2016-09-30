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
        $user->userId = '8c5a3934-31b0-465e-812d-9a2e2074d0da';
        $mock = $this->createMock(UserManager::class);
        $mock->method('getUserByProviderCreds')->willReturn($user);
        $mock->method('create');
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
                'expected' => json_encode([
                    't' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4',
                    'links' => [
                        'list_quest' => 'http://server/list-quest?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4',
                    ],
                ]),
            ]
        ];
    }
}
