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
     * @param string $questId
     *
     * @return Quest
     */
    public function getQuest($questId)
    {
        $quest = new Quest();

        $data = $this->db[$questId];

        $quest->questId     = $questId;
        $quest->title       = $data['title'];
        $quest->description = $data['description'];
        $quest->points      = $data['points'];

        return $quest;
    }
}
