<?php

namespace Kubikvest\Logger;

use \Packaged\QueryBuilder\Builder\QueryBuilder;
use Monolog\Logger;

class MysqlHandlerTest extends \PHPUnit_Extensions_Database_TestCase
{
    public function getConnection()
    {
        $dsn = 'mysql:dbname=kubikvest;host='.getenv('DB_HOST').';charset=UTF8';
        $pdo = new \PDO($dsn, 'root');

        return $this->createDefaultDBConnection($pdo);
    }

    public function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__.'/../../fixtures/kv_log.xml');
    }

    public function testGetInstance()
    {
        $actual = new MysqlHandler($this->getConnection()->getConnection(), new QueryBuilder());

        $this->assertInstanceOf('\\Kubikvest\\Logger\\MysqlHandler', $actual);
    }

    public function testLog()
    {
        $handler = new MysqlHandler($this->getConnection()->getConnection(), new QueryBuilder());
        $logger = new Logger('user.track');
        $logger->setHandlers([$handler]);
        $logger->log(Logger::INFO, 'Checkpoint', [
            'gameId' => 'sdf',
        ]);
    }
}
