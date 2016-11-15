<?php

namespace Kubikvest\Mapper;

use \Packaged\QueryBuilder\Builder\QueryBuilder;

class UserMapperTest extends \PHPUnit_Extensions_Database_TestCase
{
    protected $tableName = 'kv_user';
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
        parent::setUp();
        $this->mapper = new UserMapper($this->getConnection()->getConnection(), new QueryBuilder());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
    }

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__.'/../../fixtures/user.xml');
    }

    public function testInstance()
    {
        $actual = new UserMapper($this->getConnection()->getConnection(), new QueryBuilder());
        $this->assertInstanceOf('\\Kubikvest\\Mapper\\UserMapper', $actual);
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
                    'user_id'      => 'adff5c92-008c-47ac-bad8-11be43ea1469',
                    'provider'     => 'vk',
                    'uid'          => 1111,
                    'access_token' => 'token',
                    'group_id'     => '',
                    'ttl'          => 0,
                    'start_task'   => date_create_from_format('Y-m-d H:i:s', '1970-01-01 00:00:00'),
                ],
            ]
        ];
    }

    /**
     * @param int    $uid
     * @param string $provider
     * @param array  $expected
     * @dataProvider getUserByProviderCredsDataProvider
     */
    public function testGetUserByProviderCreds($uid, $provider, $expected)
    {
        $actual = $this->mapper->getUserByProviderCreds($uid, $provider);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @see testGetUserByProviderCreds
     * @return array
     */
    public function getUserByProviderCredsDataProvider()
    {
        return [
            [
                'uid' => 1111,
                'provider' => 'vk',
                'expected' => [
                    'user_id'      => 'adff5c92-008c-47ac-bad8-11be43ea1469',
                    'provider'     => 'vk',
                    'uid'          => 1111,
                    'access_token' => 'token',
                    'group_id'     => '',
                    'ttl'          => 0,
                    'start_task'   => date_create_from_format('Y-m-d H:i:s', '1970-01-01 00:00:00'),
                ],
            ],
        ];
    }

    public function testCreate()
    {
        $this->mapper->create(
            [
                'user_id'      => 'e56abaa3-ace5-4561-9b19-76c5fa49814b',
                'uid'          => 2222,
                'provider'     => 'vk',
                'access_token' => 'token',
                'ttl'          => 2,
            ]
        );

        $actual   = $this->getConnection()->createQueryTable($this->tableName, 'SELECT * FROM ' . $this->tableName . ' WHERE uid=2222');
        $ds = $this->createFlatXmlDataSet(__DIR__.'/../../fixtures/user-create.xml');
        $rds = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($ds);
        $rds->addFullReplacement('##NULL##', null);

        $expected = $rds->getTable($this->tableName);

        $this->assertTablesEqual($expected, $actual);
    }

    public function testUpdate()
    {
        $this->mapper->update(
            [
                'user_id'      => 'adff5c92-008c-47ac-bad8-11be43ea1469',
                'access_token' => 'token',
                'group_id'     => 'e977f86f-c2a7-4628-9823-e562a889e9b6',
                'ttl'          => 2,
                'start_task'   => '1970-01-01 00:00:00',
            ]
        );

        $actual   = $this->getConnection()->createQueryTable($this->tableName, 'SELECT * FROM ' . $this->tableName . ' WHERE uid=1111');
        $ds = $this->createFlatXmlDataSet(__DIR__.'/../../fixtures/user-update.xml');
        $rds = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($ds);
        $rds->addFullReplacement('##NULL##', null);

        $expected = $rds->getTable($this->tableName);

        $this->assertTablesEqual($expected, $actual);
    }
}
