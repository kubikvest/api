<?php

namespace Kubikvest\Manager;

use Kubikvest\Mapper\GroupMapper;
use Kubikvest\Model\Group;
use Kubikvest\Model\PinCode;
use Kubikvest\Model\Quest;
use Kubikvest\Model\Uuid;

class GroupManager
{
    protected $mapper;

    public function __construct(GroupMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function create(Quest $quest)
    {
        $group = new Group($this);

        $group->groupId = Uuid::gen();
        $group->gameId  = null;
        $group->questId = $quest->questId;
        $group->pointId = $quest->points[0];
        $group->pin     = PinCode::gen();
        $group->active  = true;

        $this->mapper->insert(
            [
                'groupId' => $group->groupId,
                'gameId'  => $group->gameId,
                'questId' => $group->questId,
                'pointId' => $group->pointId,
                'pin'     => $group->pin,
            ]
        );

        return $group;
    }

    public function addUser(Group $group)
    {
        $this->mapper->update(
            [
                'groupId'    => $group->groupId,
                'gameId'     => $group->gameId,
                'questId'    => $group->questId,
                'pointId'    => $group->pointId,
                'users'      => json_encode($group->getUsers()),
                'pin'        => $group->pin,
                'startPoint' => '',
                'active'     => $group->active,
            ]
        );
    }

    public function get($groupId, $active = true)
    {
        $data = $this->mapper->getGroup($groupId, $active);

        $group = new Group($this);
        $group->setUsers($data['users']);
        $group->groupId    = $data['groupId'];
        $group->gameId     = $data['gameId'];
        $group->questId    = $data['questId'];
        $group->pointId    = $data['pointId'];
        $group->pin        = $data['pin'];
        $group->startPoint = $data['startPoint'];
        $group->active     = $data['active'];

        return $group;
    }

    public function update(Group $group)
    {
        $this->mapper->update(
            [
                'groupId'    => $group->groupId,
                'gameId'     => $group->gameId,
                'questId'    => $group->questId,
                'pointId'    => $group->pointId,
                'users'      => json_encode($group->getUsers()),
                'pin'        => $group->pin,
                'startPoint' => $group->startPoint,
                'active'     => $group->active,
            ]
        );
    }
}
