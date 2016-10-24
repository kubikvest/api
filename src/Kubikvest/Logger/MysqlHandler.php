<?php

namespace Kubikvest\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Packaged\QueryBuilder\Assembler\QueryAssembler;
use Packaged\QueryBuilder\Builder\QueryBuilder;

class MysqlHandler extends AbstractProcessingHandler
{
    /**
     * @var \PDO
     */
    protected $pdo;

    /**
     * @var QueryBuilder
     */
    protected $queryBuilder;
    protected $table = 'kv_log';

    public function __construct(\PDO $pdo, QueryBuilder $queryBuilder, $level = Logger::DEBUG, $bubble = true)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = $queryBuilder;

        parent::__construct($level, $bubble);
    }
    protected function write(array $record)
    {
        var_dump($record);
        //$query = $this->queryBuilder->insertInto($this->table);
        //$this->pdo->exec(QueryAssembler::stringify($query));
    }
}
