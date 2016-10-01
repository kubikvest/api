<?php

namespace Kubikvest\Mapper;

use Kubikvest\Model\Generic;

class GenericMapper
{
    protected $pdo;
    protected $queryBuilder;

    public function insert(Generic $model)
    {
        $table = $model::getTable();
        $fields = $model::getFields();
        array_unshift($fields, $table);
        $statement = call_user_func_array([$this->queryBuilder, 'insertInto'], $fields);
    }
}
