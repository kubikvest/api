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
namespace Kubikvest\Resource\Prompt;

use Kubikvest\Resource\Prompt\Model\Prompt;

class Builder
{
    /**
     * @var array
     */
    private $prompts;

    /**
     * Builder constructor.
     *
     * @param array $prompts
     */
    public function __construct(array $prompts)
    {
        $this->prompts = $prompts;
    }

    /**
     * @param \DateTime $startTask
     *
     * @return Prompt
     */
    public function build(\DateTime $startTask)
    {
        $p = new Prompt();
        $since = $startTask->diff(new \DateTime());

        foreach ($this->prompts as $k => $v) {
            if ($since->i >= $k) {
                $p->setTimer($k);
                $p->setDescription($v['description']);
                $p->setTitle($v['title']);
            }
        }

        if (0 < $since->h) {
            $prompt = end($this->prompts);
            $p->setDescription($prompt['description']);
            $p->setTitle($prompt['title']);
        }

        return $p;
    }

}
