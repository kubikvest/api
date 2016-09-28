<?php

namespace Kubikvest\Model;

class User implements UserInterface
{
    public $userId = null;
    public $provider = null;
    public $avatar = null;
    public $accessToken = null;
    public $questId = null;
    public $pointId = null;
    public $startTask = null;

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->userId;
    }

    /**
     * @param string $userId
     * @param string $provider
     * @param string $avatar
     *
     * @return User
     */
    public static function signin($userId, $provider, $avatar = null)
    {
        $user = new User();

        return $user;
    }
}
