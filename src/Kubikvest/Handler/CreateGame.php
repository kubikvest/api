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

class CreateGame implements Handler
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function handle(Request $request)
    {
        $data = $this->app['request.content'];

        /**
         * @var \Kubikvest\Model\User  $user
         * @var \Kubikvest\Model\Quest $quest
         * @var \Kubikvest\Model\Group $group
         */
        $user  = $this->app['user'];
        $quest = $this->app['quest.mapper']->getQuest($data['quest_id']);
        if (empty($user->groupId)) {
            $group = $this->app['group.manager']->create($quest);
            $group->addUser($user);
            $user->groupId = $group->groupId;
            $this->app['user.manager']->update($user);
        }

        return new JsonResponse(
            [
                't'     => $this->app['link.gen']->getToken($user),
                'links' => [
                    'task' => $this->app['link.gen']->getLink(Model\LinkGenerator::TASK, $user),
                ],
            ]
        );
    }
}
