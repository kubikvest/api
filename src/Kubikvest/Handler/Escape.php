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
use Kubikvest\Resource;

class Escape implements Handler
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
         * @var Resource\User\Model\User $user
         * @var Resource\Group\Builder   $groupBuilder
         */
        $user         = $this->app[Resource\User\Model\User::class];
        $groupBuilder = $this->app[Resource\Group\Builder::class];

        try {
            $group = $groupBuilder->build($user->getGroupId());
        } catch (\Exception $e) {
            return new JsonResponse([]);
        }

        if (!$group->active) {
            return new JsonResponse([]);
        }

        /**
         * @var Resource\Group\Updater $groupUpdater
         */
        $groupUpdater = $this->app[Resource\Group\Updater::class];
        $group->active = false;
        $groupUpdater->update($group);

        return new JsonResponse([]);
    }

}
