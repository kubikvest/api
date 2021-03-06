<?php
/**
 * Class User
 * @package Kubikvest\Mapper
 */

namespace Kubikvest\Mapper;

use iMega\Formatter\DateTimeType;
use Packaged\QueryBuilder\Assembler\QueryAssembler;
use Kubikvest\Model;
use iMega\Formatter\Formatter;
use iMega\Formatter\StringType;
use iMega\Formatter\IntType;
use Packaged\QueryBuilder\Clause\SelectClause;
use Packaged\QueryBuilder\Statement\QueryStatement;

class UserMapper
{
    /**
     * @var
     */
    protected $pdo;

    /**
     * @var \Packaged\QueryBuilder\Builder\QueryBuilder
     */
    protected $queryBuilder;

    protected $table = 'kv_user';
    protected $formatter;

    /**
     * @param $pdo
     * @param $queryBuilder
     */
    public function __construct($pdo, $queryBuilder)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = $queryBuilder;
        $this->formatter = new Formatter(
            [
                StringType::setDefault('user_id', ''),
                StringType::setDefault('provider', ''),
                IntType::setDefault('uid', 0),
                StringType::setDefault('access_token', ''),
                StringType::setDefault('group_id', ''),
                IntType::setDefault('ttl', 0),
                DateTimeType::setDefault('start_task', date_create_from_format('Y-m-d H:i:s', '1970-01-01 00:00:00'))
            ]
        );
    }

    public function getUser($userId)
    {
        $record = [];
        $select = new SelectClause();
        $select->addFields($this->formatter->getFileds());
        $query = new QueryStatement();
        $query->addClause($select);
        $query->from($this->table)
            ->where(
                [
                    'user_id' => $this->formatter->getData('user_id', $userId),
                ]
            );
        try {
            $record = $this->pdo->query(QueryAssembler::stringify($query))
                ->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            //
        }

        return is_array($record) ? $this->formatter->getValueCollection($record) : [];
    }

    /**
     * @param int    $userId
     * @param string $provider
     *
     * @return array
     */
    public function getUserByProviderCreds($uid, $provider)
    {
        $record = [];
        $select = new SelectClause();
        $select->addFields($this->formatter->getFileds());
        $query = new QueryStatement();
        $query->addClause($select);
        $query->from($this->table)
            ->where(
                [
                    'provider' => $this->formatter->getData('provider', $provider),
                    'uid'      => $this->formatter->getData('uid', $uid),
                ]
            );
        try {
            $record = $this->pdo->query(QueryAssembler::stringify($query))
                ->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            //
        }

        return is_array($record) ? $this->formatter->getValueCollection($record) : [];
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        $query = $this->queryBuilder
            ->insertInto($this->table, 'user_id', 'provider', 'uid', 'access_token', 'ttl')
            ->values(
                $this->formatter->getData('user_id', $data['user_id']),
                $this->formatter->getData('provider', $data['provider']),
                $this->formatter->getData('uid', $data['uid']),
                $this->formatter->getData('access_token', $data['access_token']),
                $this->formatter->getData('ttl', $data['ttl'])
            );
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    /**
     * @param array $data
     */
    public function update(array $data)
    {
        $query = $this->queryBuilder
            ->update(
                $this->table,
                [
                    'access_token' => $this->formatter->getData('access_token', $data['access_token']),
                    'group_id'     => $this->formatter->getData('group_id', $data['group_id']),
                    'ttl'          => $this->formatter->getData('ttl', $data['ttl']),
                    'start_task'   => $this->formatter->getData('start_task', $data['start_task']),
                ]
            )
            ->where(
                [
                    'user_id' => $this->formatter->getData('user_id', $data['user_id']),
                ]
            );
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    public function truncate()
    {
        $this->pdo->exec('truncate kv_user');
        $this->pdo->exec('truncate kv_group');
    }
}
