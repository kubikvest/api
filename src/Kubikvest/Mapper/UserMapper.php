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

    /**
     * @param $pdo
     * @param $queryBuilder
     */
    public function __construct($pdo, $queryBuilder)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param int    $userId
     * @param string $provider
     *
     * @return array
     */
    public function getUser($userId, $provider)
    {
        $record = [];
        try {
            $query = $this->queryBuilder
                ->select(
                    'userId',
                    'provider',
                    'accessToken',
                    'questId',
                    'pointId',
                    'startTask'
                )
                ->from('user')
                ->where([
                    'userId'   => $userId,
                    'provider' => $provider,
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
                ->from('user')
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
     * @param Model\User $user
     *
     * @return Model\User
     */
    public function newbie(Model\User $user)
    {
        $query = $this->queryBuilder
            ->insertInto('user', 'userId', 'provider', 'accessToken', 'questId', 'pointId')
            ->values(
                $user->userId,
                $user->provider,
                $user->accessToken,
                $user->questId,
                $user->pointId
            );
        $this->pdo->exec(QueryAssembler::stringify($query));

        return $user;
    }

    /**
     * @param Model\User $user
     */
    public function update(Model\User $user)
    {
        $query = $this->queryBuilder
            ->update('user', [
                'accessToken' => $user->accessToken,
                'questId'     => $user->questId,
                'pointId'     => $user->pointId,
                'startTask'   => $user->startTask,
            ])
            ->where(['userId' => $user->userId]);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    /**
     * @param Model\User $user
     */
    public function setStartTask(Model\User $user)
    {
        $query = $this->queryBuilder
            ->update('user', [
                'startTask' => $user->startTask,
            ])
            ->where(['userId' => $user->userId]);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }
}
