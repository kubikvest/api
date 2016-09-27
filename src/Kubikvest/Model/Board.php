<?php

namespace Kubikvest\Model;

class Board implements BoardInterface
{
    /**
     * @param int   $limit
     * @param int   $offset
     * @param array $filter
     *
     * @return array
     */
    public static function listQuests($limit = 10, $offset = 0, array $filter)
    {
        $quests = [];

        return $quests;
    }
}
