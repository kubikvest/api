<?php

namespace Kubikvest\Manager;

use Kubikvest\Mapper\QuestMapper;
use Kubikvest\Model\Quest;

class QuestManager
{
    protected $mapper;

    public function __construct(QuestMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function listQuest(array $filter)
    {
        $quests = $this->mapper->listQuest([]);

        $ret = [];
        foreach ($quests as $questId => $item) {
            $quest = new Quest();
            $quest->questId     = $questId;
            $quest->title       = $item['title'];
            $quest->description = $item['description'];
            $ret[] = $quest;
        }

        return $ret;
    }
}
