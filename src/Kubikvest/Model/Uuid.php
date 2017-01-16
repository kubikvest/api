<?php

namespace Kubikvest\Model;

use Ramsey\Uuid\Uuid as LibUuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class Uuid
{
    protected $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public static function gen()
    {
        $uuid = '';
        try {
            $uuid = LibUuid::uuid4()->toString();
        } catch (UnsatisfiedDependencyException $e) {
            throw new \RuntimeException;
        }

        return $uuid;
    }
}
