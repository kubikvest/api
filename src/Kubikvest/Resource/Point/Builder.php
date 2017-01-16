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

use iMega\Formatter\Formatter;
use Kubikvest\Model\Uuid;

class Builder
{
    /**
     * @var Mapper
     */
    private $mapper;

    public function __construct(Mapper $mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param Uuid $uuid
     *
     * @return Model\Point
     */
    public function build(Uuid $uuid)
    {
        $data = $this->mapper->getPoint($uuid->getValue());

        $formatter = new Formatter(
            [
                PointFormatType::setDefault('point', null)
            ]
        );

        return $formatter->getValue('point', $data);
    }
}
