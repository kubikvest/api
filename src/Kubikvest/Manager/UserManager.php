<?php

namespace Kubikvest\Manager;

use Kubikvest\Mapper\UserMapper;
use Kubikvest\Model\User;
use Kubikvest\Model\Uuid;

class UserManager
{
    protected $mapper;

    public function __construct(UserMapper $userMapper)
    {
        $this->mapper = $userMapper;
    }

    /**
     * @param int    $uid
     * @param string $provider
     * @param string $token
     * @param int    $ttl
     *
     * @return User
     */
    public function create($uid, $provider, $token, $ttl)
    {
        $user = new User();

        $user->userId      = Uuid::gen();
        $user->provider    = $provider;
        $user->uid         = (int) $uid;
        $user->accessToken = $token;
        $user->ttl         = (int) $ttl;

        $this->mapper->create([
            'userId'      => $user->userId,
            'provider'    => $user->provider,
            'uid'         => $user->uid,
            'accessToken' => $user->accessToken,
            'ttl'         => $user->ttl,
        ]);

        return $user;
    }

    /**
     * @param int    $uid
     * @param string $provider
     * @param string $token
     * @param int    $ttl
     *
     * @return User
     */
    public function createOnlyTest($uid, $provider, $token, $ttl)
    {
        $user = new User();

        $user->userId      = '8c5a3934-31b0-465e-812d-9a2e2074d0da';
        $user->provider    = $provider;
        $user->uid         = (int) $uid;
        $user->accessToken = $token;
        $user->ttl         = (int) $ttl;

        $this->mapper->create([
            'userId'      => $user->userId,
            'provider'    => $user->provider,
            'uid'         => $user->uid,
            'accessToken' => $user->accessToken,
            'ttl'         => $user->ttl,
        ]);

        return $user;
    }

    public function getUserByProviderCreds($userId, $provider = 'vk')
    {
        $user = new User();
        $userData = $this->mapper->getUserByProviderCreds($userId, $provider);
        if (!empty($userData)) {
            $user->userId      = $userData['userId'];
            $user->provider    = $userData['provider'];
            $user->uid         = (int) $userData['uid'];
            $user->accessToken = $userData['accessToken'];
            $user->groupId     = $userData['groupId'];
            $user->ttl         = (int) $userData['ttl'];
            $user->startTask   = $userData['startTask'];
        }

        return $user;
    }

    public function getUser($userId)
    {
        $user = new User();
        $userData = $this->mapper->getUser($userId);
        if (!empty($userData)) {
            $user->userId      = $userData['userId'];
            $user->provider    = $userData['provider'];
            $user->uid         = (int) $userData['uid'];
            $user->accessToken = $userData['accessToken'];
            $user->groupId     = $userData['groupId'];
            $user->ttl         = (int) $userData['ttl'];
            $user->startTask   = $userData['startTask'];
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $this->mapper->update([
            'userId'      => $user->userId,
            'accessToken' => $user->accessToken,
            'groupId'     => $user->groupId,
            'ttl'         => $user->ttl,
            'startTask'   => $user->startTask,
        ]);
    }

    public function truncate()
    {
        $this->mapper->truncate();
    }
}
