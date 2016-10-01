<?php

namespace Kubikvest\Model;

class User
{
    public $userId      = null;
    public $provider    = null;
    public $accessToken = null;
    public $uid         = null;
    /**
     * @deprecated
     */
    public $questId     = null;
    /**
     * @deprecated
     */
    public $pointId     = null;
    public $startTask   = null;
    public $groupId     = null;
    public $ttl         = null;

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->userId;
    }

    /**
     * @return bool
     */
    public function isEmptyGroup()
    {
        return null === $this->groupId;
    }

    public static function getFields()
    {
        return [
            'userId',
            'provider',
            'accessToken',
            'kvestId',
            'pointId',
        ];
    }
}
