<?php

namespace Kubikvest\Model;

use Firebase\JWT\JWT;

/**
 * Class LinkGenerator
 * @package Kubikvest\Model
 */
class LinkGenerator
{
    const TASK       = 'task';
    const CHECKPOINT = 'checkpoint';
    const FINISH     = 'finish';

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
     * @param int    $ttl
     * @param string $provider
     */
    public function getLink($type, User $user, $ttl = 0, $provider = 'vk')
    {
        $token = JWT::encode(
            [
                'auth_provider' => $provider,
                'user_id'       => $user->userId,
                'ttl'           => $ttl,
                'quest_id'      => $user->questId,
                'point_id'      => $user->pointId,
            ],
            $this->key
        );

        return sprintf("%s/%s?t=%s", $this->url, $type, $token);
    }
}
