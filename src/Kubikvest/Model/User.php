<?php

namespace Kubikvest\Model;

class User
{
    public $userId = null;
    public $provider = null;
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
