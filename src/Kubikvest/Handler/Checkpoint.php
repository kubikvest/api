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

use Kubikvest\Resource;
use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Kubikvest\Model;

class Checkpoint implements Handler
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function handle(Request $request)
    {
        $data = $this->app['request.content'];

        /**
         * @var \Kubikvest\Model\User  $user
         * @var \Kubikvest\Model\Group $group
         * @var \Kubikvest\Model\Quest $quest
         * @var \Kubikvest\Model\Point $point
         * @var \Kubikvest\Manager\PointManager $pointManager
         */
        $user  = $this->app['user'];
        $group = $this->app['group.manager']->get($user->groupId);
        $quest = $this->app['quest.mapper']->getQuest($group->questId);
        $point = $this->app['point.mapper']->getPoint($group->pointId);
        $pointManager = $this->app['point.manager'];

        $response = [
            't'            => $this->app['link.gen']->getToken($user),
            'quest'        => (array) $quest,
            'point'        => (array) $point,
            'total_points' => count($quest->points),
            'finish'       => false,
        ];
        unset($response['point']['prompt']);
        $this->app['logger']->log(
            \Psr\Log\LogLevel::INFO,
            'Checkout',
            [
                'lat' => $data['lat'],
                'lng' => $data['lng'],
                'acr' => $data['acr'],
            ]
        );

        $response['coords'] = [
            'lat' => $data['lat'] . '(' . (double)$data['lat'] . ')',
            'lng' => $data['lng'] . '(' . (double)$data['lng'] . ')',
            'acr' => $data['acr'],
        ];

        if (!$pointManager->checkCoordinates($point->coords, (double)$data['lat'], (double)$data['lng'])) {
            $distances = $pointManager->calcDistanceToPointsSector($point->coords, (double)$data['lat'], (double)$data['lng']);
            $response['distance'] = min($distances);
            $this->app['logger']->log(
                \Psr\Log\LogLevel::INFO,
                'distance',
                [
                    'min_distance' => min($distances),
                    'distance_border' => $pointManager->distanceBorderSector($distances),
                ]
            );

            if ($pointManager->distanceBorderSector($distances) > (int)$data['acr'] ||
                !$pointManager->pointIncludedAccuracyRange((int)$data['acr'], min($distances))
            ){
                $response['links']['checkpoint'] = $this->app['link.gen']
                    ->getLink(Model\LinkGenerator::CHECKPOINT, $user);
                $response['error'] = 'Не верное место отметки.';

                return new JsonResponse($response, JsonResponse::HTTP_OK);
            }
        }

        $group->startPoint = null;

        if ($group->pointId == end($quest->points)) {
            $response['links']['finish'] = $this->app['link.gen']->getLink(Model\LinkGenerator::FINISH, $user);
            $group->pointId = null;
            $this->app['group.manager']->update($group);
            $response['finish'] = true;
        } else {
            $group->pointId = $quest->nextPoint($group->pointId);
            $this->app['group.manager']->update($group);
            $response['links']['task'] = $this->app['link.gen']->getLink(Model\LinkGenerator::TASK, $user);
        }

        $this->app['logger']->log(
            \Psr\Log\LogLevel::INFO,
            'Пройдена точка',
            [
                'finish' => $response['finish'],
            ]
        );

        return new JsonResponse($response, JsonResponse::HTTP_OK);
    }
}
