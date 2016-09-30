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
    public function testGetUser($expected)
    {
        $actual = $this->mapper->getUser('1111', 'vk');

        $this->assertEquals($expected, $actual);
    }

    public function getUserDataProvider()
    {
        return [
            [
                'expected' => [
                    'userId'      => '1111',
                    'provider'    => 'vk',
                    'accessToken' => 'token',
                    'groupId'     => null,
                    'ttl'         => null,
                    'questId'     => null,
                    'pointId'     => null,
                    'startTask'   => null,
                ],
            ]
        ];
    }
}
