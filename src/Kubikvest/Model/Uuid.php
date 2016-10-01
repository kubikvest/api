<?php

namespace Kubikvest\Model;

use Ramsey\Uuid\Uuid as LibUuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

final class Uuid
{
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
