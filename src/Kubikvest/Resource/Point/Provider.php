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

namespace Kubikvest\Resource\Point;

use Pimple\Container;
use Pimple\ServiceProviderInterface;

class Provider implements ServiceProviderInterface
{
    public function register(Container $pimple)
    {
        $pimple[Mapper::class] = function () use ($pimple) {
            return new Mapper($pimple['points']);
        };
        $pimple[Builder::class] = function () use ($pimple) {
            return new Builder($pimple[Mapper::class]);
        };
    }
}
