<?php

namespace Kubikvest\Model;

interface UserInterface
{
    /**
     * @param string $userId
     * @param string $provider
     * @param string $avatar
     *
     * @return User
     */
    public static function signin($userId, $provider, $avatar = null);
    public function checkPoint($lat, $lon);
    public function getTask($uuid);
    public function getNextTask();
    public function makeGame(Quest $quest);
}
