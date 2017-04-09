<?php
/**
 * Copyright (C) 2017. iMega ltd Dmitry Gavriloff (email: info@imega.ru),
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Kubikvest\Resource;

use Firebase\JWT\JWT;

class Token
{
    private $key;
    private $ttl;

    /**
     * Token constructor.
     *
     * @param string $key
     * @param        $ttl
     */
    public function __construct($key, $ttl)
    {
        $this->key = $key;
        $this->ttl = $ttl;
    }

    /**
     * @return string
     */
    public function gen($aud)
    {
        return JWT::encode(['aud' => $aud, 'exp' => time() + $this->ttl], $this->key);
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public function getAud($token)
    {
        $data = JWT::decode($token, $this->key, ['HS256']);
        return $data->aud;
    }
}
