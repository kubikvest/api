<?php

namespace Kubikvest\Model;

use Kubikvest\Manager\GroupManager;

class Group extends Generic implements GroupInterface
{
    public $groupId = null;
    public $gameId  = null;
    public $questId = null;
    public $pointId = null;
    protected $users   = [];
    public $pin     = null;
    public $active  = true;

    /**
     * @var GroupManager
     */
    protected $manager;

    public static function getTable()
    {
        return 'group';
    }

    public static function getFields()
    {
        return [
            'gameId',
            'questId',
            'pointId',
            'users',
            'pin',
            'active',
        ];
    }

    public function __construct(GroupManager $manager)
    {
        $this->manager = $manager;
    }

    public function addUser(User $user)
    {
        array_push($this->users, $user->userId);
        $this->manager->addUser($this);
    }

    public function getTask($uuid)
    {

    }

    public function getNextTask()
    {

    }

    public function remove()
    {
        $this->active = false;
        $this->mapper->update($this);
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = json_decode($users, true);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->groupId;
    }
}
