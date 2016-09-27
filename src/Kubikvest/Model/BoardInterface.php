<?php

namespace Kubikvest\Model;

interface BoardInterface
{
    public static function listQuests($limit = 10, $offset = 0, array $filter);
}
