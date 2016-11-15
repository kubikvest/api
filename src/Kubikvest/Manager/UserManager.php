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
        $user->uid         = $uid;
        $user->accessToken = $token;
        $user->ttl         = $ttl;

        $this->mapper->create([
            'user_id'      => $user->userId,
            'provider'     => $user->provider,
            'uid'          => $user->uid,
            'access_token' => $user->accessToken,
            'ttl'          => $user->ttl,
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
        $user->uid         = $uid;
        $user->accessToken = $token;
        $user->ttl         = $ttl;

        $this->mapper->create([
            'user_id'      => $user->userId,
            'provider'     => $user->provider,
            'uid'          => $user->uid,
            'access_token' => $user->accessToken,
            'ttl'          => $user->ttl,
        ]);

        return $user;
    }

    public function getUserByProviderCreds($userId, $provider = 'vk')
    {
        $user = new User();
        $userData = $this->mapper->getUserByProviderCreds($userId, $provider);
        if (!empty($userData)) {
            $user->userId      = $userData['user_id'];
            $user->provider    = $userData['provider'];
            $user->uid         = $userData['uid'];
            $user->accessToken = $userData['access_token'];
            $user->groupId     = $userData['group_id'];
            $user->ttl         = $userData['ttl'];
            $user->startTask   = $userData['start_task'];
        }

        return $user;
    }

    public function getUser($userId)
    {
        $user = new User();
        $userData = $this->mapper->getUser($userId);
        if (!empty($userData)) {
            $user->userId      = $userData['user_id'];
            $user->provider    = $userData['provider'];
            $user->uid         = $userData['uid'];
            $user->accessToken = $userData['access_token'];
            $user->groupId     = $userData['group_id'];
            $user->ttl         = $userData['ttl'];
            $user->startTask   = $userData['start_task'];
        }

        return $user;
    }

    /**
     * @param User $user
     */
    public function update(User $user)
    {
        $this->mapper->update([
            'user_id'      => $user->userId,
            'access_token' => $user->accessToken,
            'group_id'     => $user->groupId,
            'ttl'          => $user->ttl,
            'start_task'   => $user->startTask,
        ]);
    }

    public function truncate()
    {
        $this->mapper->truncate();
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isEmpty(User $user)
    {
        return empty($user->userId);
    }

    /**
     * @param User $user
     *
     * @return bool
     */
    public function isEmptyGroup(User $user)
    {
        return empty($user->groupId);
    }
}
