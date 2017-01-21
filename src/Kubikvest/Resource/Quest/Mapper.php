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

namespace Kubikvest\Resource\Quest;

use iMega\Formatter\Formatter;
use iMega\Formatter\JsonType;
use iMega\Formatter\StringType;

class Mapper
{
    protected $db;

    /**
     * Mapper constructor.
     *
     * @param array $db
     */
    public function __construct(array $db)
    {
        $this->db = $db;
        $this->formatter = new Formatter(
            [
                StringType::setDefault('questid', ''),
                StringType::setDefault('title', ''),
                StringType::setDefault('description', ''),
                StringType::setDefault('picture', ''),
                JsonType::setDefault('points', []),
            ]
        );
    }

    /**
     * @param string $questId
     *
     * @return array
     */
    public function getQuest($questId)
    {
        $data = $this->db[$questId];

        $points = $this->formatter->getData('points', $data['points']);

        return [
            'questid'     => $this->formatter->getValue('questid', $questId),
            'title'       => $this->formatter->getValue('title', $data['title']),
            'description' => $this->formatter->getValue('description', $data['description']),
            'picture'     => $this->formatter->getValue('picture', $data['picture']),
            'points'      => $this->formatter->getValue('points', $points),
        ];
    }

    public function listQuest(array $filter)
    {
        return $this->db;
    }
}
