<?php

namespace Kubikvest\Mapper;

use \Packaged\QueryBuilder\Builder\QueryBuilder;

class UserMapperTest extends \PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var UserMapper
     */
    protected $mapper;
    protected $pdo;

    public function getConnection()
    {
        $dsn = 'mysql:dbname=kubikvest;host='.getenv('DB_HOST').';charset=UTF8';
        $pdo = new \PDO($dsn, 'root');

        return $this->createDefaultDBConnection($pdo);
    }

    public function setUp()
    {
        $this->mapper = new UserMapper($this->getConnection()->getConnection(), new QueryBuilder());
    }

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__.'/../../fixtures/user.xml');
    }

    /**
     * @dataProvider getUserDataProvider
     */
    public function testGetUser($user, $expected)
    {
        $actual = $this->mapper->getUser($user['userId']);

        $this->assertEquals($expected, $actual);
    }

    public function getUserDataProvider()
    {
        return [
            [
                'user' => [
                    'userId' => 'adff5c92-008c-47ac-bad8-11be43ea1469',
                ],
                'expected' => [
                    'userId'      => 'adff5c92-008c-47ac-bad8-11be43ea1469',
                    'provider'    => 'vk',
                    'uid'         => 1111,
                    'accessToken' => 'token',
                    'groupId'     => null,
                    'ttl'         => null,
                    'startTask'   => null,
                ],
            ]
        ];
    }
}
