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

namespace Kubikvest\Handler;

use Kubikvest\Model;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ListQuest implements Handler
{
    /**
     * @var Application
     */
    private $app;
    private $excludeUsers = [
        //380117337 => 0,
        1222731   => 0,
    ];

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request)
    {
        /**
         * @var \Kubikvest\Model\User $user
         */
        $user   = $this->app['user'];
        $quests = $this->app['quest.manager']->listQuest([]);

        $data = [];

        if ('8c5a3934-31b0-465e-812d-9a2e2074d0da' != $user->userId) {
            array_shift($quests);
        }

        if (!isset($this->excludeUsers[$user->uid])) {
            unset($quests['d53c9c0c-75dc-4816-862f-20913b1cdd19']);
            unset($quests['515ca01a-7b22-4f16-aa3e-7f0da736331d']);
        }

        foreach ($quests as $item) {
            /**
             * @var \Kubikvest\Model\Quest $item
             */
            $data[] = [
                'quest_id'    => $item->questId,
                'title'       => $item->title,
                'description' => $item->description,
                'picture'     => $item->picture,
                'link'        => $this->app['link.gen']->getLink(Model\LinkGenerator::CREATE_GAME, $user),
            ];
            if ('8c5a3934-31b0-465e-812d-9a2e2074d0da' == $user->userId) {
                break;
            }
        }

        return new JsonResponse(
            [
                't'      => $this->app['link.gen']->getToken($user),
                'quests' => $data,
            ]
        );
    }

}
