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

class Auth implements Handler
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
        $code = $request->get('code');

        try {
            $response = $this->app['curl']->request('GET', '/access_token', [
                'query' => [
                    'client_id'     => $this->app['client_id'],
                    'client_secret' => $this->app['client_secret'],
                    'redirect_uri'  => $this->app['redirect_uri'],
                    'code'          => $code,
                ]
            ]);
        } catch (\RuntimeException $e) {
            return new JsonResponse(
                [
                    'error' => $e->getMessage(),
                ],
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if (JsonResponse::HTTP_UNAUTHORIZED == $response->getStatusCode()) {
            return new JsonResponse([], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $data = json_decode($response->getBody()->__toString(), true);

        if (!isset($data['user_id'])) {
            return new JsonResponse([], JsonResponse::HTTP_BAD_REQUEST);
        }

        /**
         * @var \Kubikvest\Model\User $user
         */
        $user = $this->app['user.manager']->getUserByProviderCreds($data['user_id'], 'vk');

        if ($user->isEmpty()) {
            if (222 == $code) {
                $user = $this->app['user.manager']->createOnlyTest($data['user_id'], 'vk', $data['access_token'], $data['expires_in']);
            } else {
                $user = $this->app['user.manager']->create($data['user_id'], 'vk', $data['access_token'], $data['expires_in']);
            }
        } else {
            $user->accessToken = $data['access_token'];
            $user->ttl         = $data['expires_in'];
            $this->app['user.manager']->update($user);
        }

        if ($user->isEmptyGroup()) {
            $links['list_quest'] = $this->app['link.gen']->getLink(Model\LinkGenerator::LIST_QUEST, $user);
        } else {
            $links['task'] = $this->app['link.gen']->getLink(Model\LinkGenerator::TASK, $user);
        }

        return new JsonResponse([
            't'     => $this->app['link.gen']->getToken($user),
            'links' => $links,
        ]);
    }

}
