<?php

namespace Kubikvest\Model;

class User
{
    public $userId      = '';
    public $provider    = '';
    public $accessToken = '';
    public $uid         = 0;
    /**
     * @deprecated
     */
    public $questId     = '';
    /**
     * @deprecated
     */
    public $pointId     = '';
    /**
     * @var \DateTime
     */
    public $startTask   = '';
    public $groupId     = '';
    /**
     * @var int
     */
    public $ttl         = 0;

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->userId);
    }

    /**
     * @return bool
     */
    public function isEmptyGroup()
    {
        return empty($this->groupId);
    }
}
