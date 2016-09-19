<?php

namespace Kubikvest\Model;

class Quest
{
    public $questId = null;
    public $title = null;
    public $description = null;

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return null === $this->questId;
    }
}
