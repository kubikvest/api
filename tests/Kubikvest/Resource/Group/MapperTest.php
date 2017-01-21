<?php
/**
 * Copyright (C) 2017. iMega ltd Dmitry Gavriloff (email: info@imega.ru),
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Kubikvest\Resource\Group;

use Packaged\QueryBuilder\Builder\QueryBuilder;

class MapperTest extends \PHPUnit_Extensions_Database_TestCase
{
    protected $tableName = 'kv_group';
    /**
     * @var
     */
    protected $mapper;
    protected $pdo;

    public function getConnection()
    {
        $dsn = 'mysql:dbname=kubikvest;host=' . getenv('DB_HOST') . ';charset=UTF8';
        $pdo = new \PDO($dsn, 'root');

        return $this->createDefaultDBConnection($pdo);
    }

    public function setUp()
    {
        parent::setUp();
        $this->mapper = new Mapper($this->getConnection()->getConnection(), new QueryBuilder());
        $this->getDatabaseTester()->setDataSet($this->getDataSet());
    }

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/../../../fixtures/group.xml');
    }

    public function testInstance()
    {
        $actual = new Mapper($this->getConnection()->getConnection(), new QueryBuilder());
        $this->assertInstanceOf(Mapper::class, $actual);
    }

    /**
     * @param $data
     * @param $expected
     *
     * @dataProvider getGroupDataProvider
     */
    public function testGetGroup($data, $expected)
    {
        $mapper = new Mapper($this->getConnection()->getConnection(), new QueryBuilder());
        $actual = $mapper->getGroup($data);
        $this->assertEquals($expected, $actual);
    }

    public function getGroupDataProvider()
    {
        return [
            [
                'data'     => 'adff5c92-008c-47ac-bad8-11be43ea1469',
                'expected' => [
                    'groupid'    => "adff5c92-008c-47ac-bad8-11be43ea1469",
                    'gameid'     => "7c12bdb0-b1d5-4f8b-867c-2572a712c3f9",
                    'questid'    => "919e9463-51a1-48b9-b1e3-bf871eef7b1f",
                    'pointid'    => "df8d418a-8d09-46e3-8d98-75e00fcc138f",
                    'users'      => [
                        "84e4c4e4-c27d-465a-8155-cf36fbff1536"
                    ],
                    'pin'        => "1020",
                    'startpoint' => date_create_from_format('Y-m-d H:i:s', '2017-01-17 20:46:51'),
                    'active'     => true,
                ],
            ],
        ];
    }

    public function testInsert()
    {
        $mapper = new Mapper($this->getConnection()->getConnection(), new QueryBuilder());
        $mapper->insert(
            [
                'groupId' => 'ddff5c92-008c-47ac-bad8-11be43eb1469',
                'gameId'  => 'df8d418a-8d09-46e3-8d98-75e00fcc138f',
                'questId' => '7c12bdb0-b1d5-4f8b-867c-2572a712c3f9',
                'pointId' => '919e9463-51a1-48b9-b1e3-bf871eef7b1f',
                'pin'     => '9077',
            ]
        );
        $actual = $this->getConnection()->createQueryTable(
            $this->tableName,
            "SELECT * FROM {$this->tableName} WHERE groupid='ddff5c92-008c-47ac-bad8-11be43eb1469'"
        );
        $ds     = $this->createFlatXmlDataSet(__DIR__ . '/../../../fixtures/group-insert.xml');
        $rds    = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($ds);
        $rds->addFullReplacement('##NULL##', null);

        $expected = $rds->getTable($this->tableName);

        $this->assertTablesEqual($expected, $actual);
    }

    public function testUpdate()
    {
        $mapper = new Mapper($this->getConnection()->getConnection(), new QueryBuilder());
        $mapper->update(
            [
                'groupId' => 'adff5c92-008c-47ac-bad8-11be43ea1469',
                'gameId'  => 'df8d418a-8d09-46e3-8d98-75e00fcc138f',
                'questId' => '7c12bdb0-b1d5-4f8b-867c-2572a712c3f9',
                'pointId' => '919e9463-51a1-48b9-b1e3-bf871eef7b1f',
                'users' => ['ae9e9bf3-51a1-48b9-b1e3-bffb2eef7baa'],
                'pin'     => '9077',
                'startPoint' => date_create_from_format('Y-m-d H:i:s', '2017-01-21 10:03:47'),
                'active' => false,
            ]
        );

        $actual = $this->getConnection()->createQueryTable(
            $this->tableName,
            "SELECT * FROM {$this->tableName} WHERE groupid='adff5c92-008c-47ac-bad8-11be43ea1469'"
        );
        $ds     = $this->createFlatXmlDataSet(__DIR__ . '/../../../fixtures/group-update.xml');
        $rds    = new \PHPUnit_Extensions_Database_DataSet_ReplacementDataSet($ds);
        $rds->addFullReplacement('##NULL##', null);

        $expected = $rds->getTable($this->tableName);

        $this->assertTablesEqual($expected, $actual);
    }
}
