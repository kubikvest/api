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

use Packaged\QueryBuilder\Assembler\QueryAssembler;

class Mapper
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
                'startPoint' => $group['startPoint'],
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
                    'startPoint',
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

