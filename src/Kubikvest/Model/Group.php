<?php

namespace Kubikvest\Model;

use Kubikvest\Mapper\GroupMapper;

class Group implements GroupInterface
{
    protected $users   = [];
    protected $gameId  = null;
    protected $questId = null;
    protected $pointId = null;
    protected $pin     = null;

    protected $mapper = null;

    public function __construct(GroupMapper $groupMapper)
    {
        $this->mapper = $groupMapper;
    }

    public function setQuest(Quest $quest)
    {
        $this->questId = $quest->questId;
        $this->mapper->update($this);
    }

    public function addUser(User $user)
    {

    }

    public function getTask($uuid)
    {

    }

    public function getNextTask()
    {

    }
}
