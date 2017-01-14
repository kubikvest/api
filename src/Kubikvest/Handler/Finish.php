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

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Finish implements Handler
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
        /**
         * @var \Kubikvest\Model\User  $user
         * @var \Kubikvest\Model\Group $group
         */
        $user = $this->app['user'];
        $group = $this->app['group.manager']->get($user->groupId);

        if (! $group->isEmpty()) {
            $group->active = false;
            $this->app['group.manager']->update($group);
        }

        $user->groupId = null;
        $this->app['user.manager']->update($user);

        return new JsonResponse([], JsonResponse::HTTP_OK);
    }
}
