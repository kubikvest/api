<?php

namespace Kubikvest\Mapper;

use Kubikvest\Model\Group;

class GroupMapper
{
    protected $pdo;
    protected $queryBuilder;

    public function __construct($pdo, $queryBuilder)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = $queryBuilder;
    }
    public function update(Group $group)
    {
        $query = $this->queryBuilder
            ->update('group', [

            ])
            ->where(['userId' => $user->userId]);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }
}
