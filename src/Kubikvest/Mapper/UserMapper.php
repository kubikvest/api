<?php

namespace Kubikvest\Mapper;

use Packaged\QueryBuilder\Assembler\QueryAssembler;
use Kubikvest\Model;

/**
 * Class User
 * @package Kubikvest\Mapper
 */
class UserMapper
{
    /**
     * @var
     */
    protected $pdo;

    /**
     * @var
     */
    protected $queryBuilder;

    protected $table = 'kv_user';

    /**
     * @param $pdo
     * @param $queryBuilder
     */
    public function __construct($pdo, $queryBuilder)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = $queryBuilder;
    }

    public function getUser($userId)
    {
        $record = [];
        try {
            $query = $this->queryBuilder
                ->select(
                    'userId',
                    'provider',
                    'uid',
                    'accessToken',
                    'groupId',
                    'ttl',
                    'startTask'
                )
                ->from($this->table)
                ->where([
                    'userId'   => $userId,
                ]);
            $record = $this->pdo->query(QueryAssembler::stringify($query))
                ->fetch(\PDO::FETCH_ASSOC);
        } catch(\Exception $e) {
            //
        }

        return $record;
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
        try {
            $query = $this->queryBuilder
                ->select(
                    'userId',
                    'provider',
                    'uid',
                    'accessToken',
                    'groupId',
                    'ttl',
                    'startTask'
                )
                ->from($this->table)
                ->where([
                    'provider' => $provider,
                    'uid'      => $uid,
                ]);
            $record = $this->pdo->query(QueryAssembler::stringify($query))
                ->fetch(\PDO::FETCH_ASSOC);
        } catch(\Exception $e) {
            //
        }

        return $record;
    }

    /**
     * @param string $accessToken
     *
     * @return Model\User
     */
    public function getIdByToken($accessToken)
    {
        $user = new Model\User();
        try {
            $query = $this->queryBuilder
                ->select('userId')
                ->from($this->table)
                ->where(['accessToken' => $accessToken]);
            $record = $this->pdo->exec(QueryAssembler::stringify($query))
                ->fetch();
            if (!empty($record)) {
                $user->userId = $record['userId'];
                $user->accessToken = $record['accessToken'];
            }
        } catch(\Exception $e) {
            //
        }

        return $user;
    }

    /**
     * @param array $data
     */
    public function create(array $data)
    {
        $query = $this->queryBuilder
            ->insertInto($this->table, 'userId', 'provider', 'uid', 'accessToken', 'ttl')
            ->values(
                $data['userId'],
                $data['provider'],
                $data['uid'],
                $data['accessToken'],
                $data['ttl']
            );
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    /**
     * @param array $data
     */
    public function update(array $data)
    {
        $query = $this->queryBuilder
            ->update($this->table, [
                'accessToken' => $data['accessToken'],
                'groupId'     => $data['groupId'],
                'ttl'         => $data['ttl'],
                'startTask'   => $data['startTask'],
            ])
            ->where([
                'userId' => $data['userId'],
            ]);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    /**
     * @param Model\User $user
     */
    public function setStartTask(Model\User $user)
    {
        $query = $this->queryBuilder
            ->update($this->table, [
                'startTask' => $user->startTask,
            ])
            ->where(['userId' => $user->userId]);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }
}
