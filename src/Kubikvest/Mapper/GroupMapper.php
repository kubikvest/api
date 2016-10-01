<?php

namespace Kubikvest\Mapper;

use Packaged\QueryBuilder\Assembler\QueryAssembler;

class GroupMapper
{
    protected $pdo;
    protected $queryBuilder;
    protected $table = 'kv_group';

    public function __construct($pdo, $queryBuilder)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = $queryBuilder;
    }

    public function insert(array $data)
    {
        $query = $this->queryBuilder
            ->insertInto($this->table, 'groupId', 'gameId', 'questId', 'pointId', 'pin', 'active')
            ->values($data['groupId'], $data['gameId'], $data['questId'], $data['pointId'], $data['pin'], true);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    public function update(array $group)
    {
        $query = $this->queryBuilder
            ->update($this->table, [
                'gameId'  => $group['gameId'],
                'questId' => $group['questId'],
                'pointId' => $group['pointId'],
                'users'   => $group['users'],
                'pin'     => $group['pin'],
                'active'  => $group['active'],
            ])
            ->where([
                'groupId' => $group['groupId']
            ]);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    public function getGroup($groupId, $active = true)
    {
        $record = [];
        try {
            $query = $this->queryBuilder
                ->select(
                    'groupId',
                    'gameId',
                    'questId',
                    'pointId',
                    'users',
                    'pin',
                    'active'
                )
                ->from($this->table)
                ->where([
                    'groupId' => $groupId,
                    'active'  => $active,
                ]);
            $record = $this->pdo->query(QueryAssembler::stringify($query))
                ->fetch(\PDO::FETCH_ASSOC);
        } catch(\Exception $e) {
            //
        }

        return $record;
    }
}
