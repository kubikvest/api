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
use Kubikvest\Resource\Prompt\Builder;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Task implements Handler
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
         * @var \Kubikvest\Model\Quest $quest
         * @var \Kubikvest\Model\Group $group
         * @var \Kubikvest\Model\Point $point
         */
        $user  = $this->app['user'];
        $group = $this->app['group.manager']->get($user->groupId);
        $quest = $this->app['quest.mapper']->getQuest($group->questId);
        $point = $this->app['point.mapper']->getPoint($group->pointId);

        if (null === $group->startPoint) {
            $group->startPoint = date('Y-m-d H:i:s');
            $this->app['group.manager']->update($group);
        }

        $response                    = [
            'quest'        => (array) $quest,
            'point'        => (array) $point,
            'timer'        => $point->getTimer($group->startPoint),
            't'            => $this->app['link.gen']->getToken($user),
            'links'        => [
                'checkpoint' => $this->app['link.gen']->getLink(Model\LinkGenerator::CHECKPOINT, $user),
            ],
            'total_points' => count($quest->points),
        ];
        $response['point']['prompt'] = $point->getPrompt($group->startPoint);
        $response['point']['point_id'] = $response['point']['pointId'];

        $prompt = (new Builder($point->prompt))->build(new \DateTime($group->startPoint));
        $response['point']['prompt'] = [
            'timer'       => $prompt->getTimer(),
            'title'       => $prompt->getTitle(),
            'description' => $prompt->getDescription(),
        ];

        return new JsonResponse($response);
    }
}
