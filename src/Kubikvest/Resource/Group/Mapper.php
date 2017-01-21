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

use iMega\Formatter\BoolType;
use iMega\Formatter\DateTimeType;
use iMega\Formatter\Formatter;
use iMega\Formatter\JsonType;
use iMega\Formatter\StringType;
use Packaged\QueryBuilder\Assembler\QueryAssembler;
use Packaged\QueryBuilder\Clause\SelectClause;
use Packaged\QueryBuilder\Statement\QueryStatement;

class Mapper
{
    protected $pdo;
    protected $queryBuilder;
    protected $table = 'kv_group';
    private $formatter;

    public function __construct($pdo, $queryBuilder)
    {
        $this->pdo = $pdo;
        $this->queryBuilder = $queryBuilder;
        $this->formatter = new Formatter(
            [
                StringType::setDefault('groupid', ''),
                StringType::setDefault('gameid', ''),
                StringType::setDefault('questid', ''),
                StringType::setDefault('pointid', ''),
                JsonType::setDefault('users', []),
                StringType::setDefault('pin', ''),
                DateTimeType::setDefault('startpoint', date_create_from_format('Y-m-d H:i:s', '1970-01-01 00:00:00')),
                BoolType::setDefault('active', true),
            ]
        );
    }

    /**
     * @param array $data
     */
    public function insert(array $data)
    {
        $query = $this->queryBuilder
            ->insertInto($this->table, 'groupId', 'gameId', 'questId', 'pointId', 'pin', 'active')
            ->values(
                $this->formatter->getData('groupid', $data['groupId']),
                $this->formatter->getData('gameid', $data['gameId']),
                $this->formatter->getData('questid', $data['questId']),
                $this->formatter->getData('pointid', $data['pointId']),
                $this->formatter->getData('pin', $data['pin']),
                $this->formatter->getData('active', true)
            );
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    public function update(array $data)
    {
        $query = $this->queryBuilder
            ->update($this->table, [
                'gameId'  => $this->formatter->getData('gameid', $data['gameId']),
                'questId' => $this->formatter->getData('questid', $data['questId']),
                'pointId' => $this->formatter->getData('pointid', $data['pointId']),
                'users'   => $this->formatter->getData('users', $data['users']),
                'pin'     => $this->formatter->getData('pin', $data['pin']),
                'startPoint' => $this->formatter->getData('startpoint', $data['startPoint']),
                'active'  => $this->formatter->getData('active', $data['active']),
            ])
            ->where([
                'groupId' => $this->formatter->getData('groupid', $data['groupId'])
            ]);
        $this->pdo->exec(QueryAssembler::stringify($query));
    }

    /**
     * @param string $groupId
     * @param bool   $active
     *
     * @return array
     */
    public function getGroup($groupId, $active = true)
    {
        $record = [];
        $select = new SelectClause();
        $select->addFields($this->formatter->getFileds());
        $query = new QueryStatement();
        $query->addClause($select);
        $query->from($this->table)
            ->where(
                [
                    'groupid' => $this->formatter->getData('groupid', $groupId),
                    'active' => $this->formatter->getData('active', $active),
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
}

