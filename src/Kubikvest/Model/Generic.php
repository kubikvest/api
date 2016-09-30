<?php

namespace Kubikvest\Model;

abstract class Generic
{
    /**
     * @return string
     */
    public static function getTable(){}

    /**
     * @return array
     */
    public static function getFields(){}
}
