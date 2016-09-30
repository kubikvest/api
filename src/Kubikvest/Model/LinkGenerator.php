<?php

namespace Kubikvest\Model;

use Firebase\JWT\JWT;

/**
 * Class LinkGenerator
 * @package Kubikvest\Model
 */
class LinkGenerator
{
    const TASK        = 'task';
    const CHECKPOINT  = 'checkpoint';
    const FINISH      = 'finish';
    const QUEST       = 'quest';
    const LIST_QUEST  = 'list-quest';
    const CREATE_GAME = 'create-game';

    protected $key;
    protected $url;

    public function __construct($url, $key)
    {
        $this->url = $url;
        $this->key = $key;
    }

    /**
     * @param string $type
     * @param User   $user
     */
    public function getLink($type, User $user, $ttl = 0, $provider = 'vk')
    {
        $token = JWT::encode(['user_id' => $user->userId], $this->key);

        return sprintf("%s/%s?t=%s", $this->url, $type, $token);
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getToken(User $user)
    {
        return JWT::encode(['user_id' => $user->userId], $this->key);
    }
}
