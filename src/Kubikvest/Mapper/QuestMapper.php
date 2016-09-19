<?php

namespace Kubikvest\Mapper;

use Kubikvest\Model\Quest;

/**
 * Class Quest
 * @package Kubikvest\Mapper
 */
class QuestMapper
{
    protected $db;
    /**
     * @param $db
     */
    public function __construct(array $db)
    {
        $this->db = $db;
    }

    /**
     * @param $id
     *
     * @return Quest
     */
    public function getQuest($id)
    {
        $quest = new Quest();

        $quest->questId     = $this->db[$id]['id'];
        $quest->title       = $this->db[$id]['title'];
        $quest->description = $this->db[$id]['description'];

        $point = new Point();
        $quest->currentPoint = $point;
    }
}
