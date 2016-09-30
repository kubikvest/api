<?php

namespace Kubikvest\Model;

final class PinCode
{
    public static function gen()
    {
        $pin = '';
        try {
            $pin = random_int(1111, 9999);
        } catch (UnsatisfiedDependencyException $e) {
            throw new \RuntimeException;
        }

        return $pin;
    }
}
