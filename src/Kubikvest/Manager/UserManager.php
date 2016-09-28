<?php

namespace Kubikvest\Manager;

use Kubikvest\Mapper\UserMapper;
use Kubikvest\Model\User;

class UserManager
{
    protected $mapper;

    public function __construct(UserMapper $userMapper)
    {
        $this->mapper = $userMapper;
    }
    public function signin($userId, $provider, $avatar = null)
    {
        $user = new User();

        return $user;
    }

    public function getUser($userId, $provider = 'vk')
    {
        $user = new User();
        $userData = $this->mapper->getUser($userId, $provider);
        if (!empty($userData)) {
            $user->userId      = (int) $userData['userId'];
            $user->provider    = $userData['provider'];
            $user->accessToken = $userData['accessToken'];
            $user->questId     = $userData['questId'];
            $user->pointId     = $userData['pointId'];
            $user->startTask   = $userData['startTask'];
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function newbie($user)
    {
        $this->mapper->newbie($user);
    }

    /**
     * @deprecate
     * @param User $user
     */
    public function update($user)
    {
        $this->mapper->update($user);
    }
}
