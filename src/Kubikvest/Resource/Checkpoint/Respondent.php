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

namespace Kubikvest\Resource\Checkpoint;

use Kubikvest\Resource\Error;
use Symfony\Component\HttpFoundation\JsonResponse;

class Respondent implements \Kubikvest\Resource\Respondent
{
    /**
     * @var Response
     */
    private $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function response()
    {
        $headers = [];
        $status  = JsonResponse::HTTP_OK;

        $data                 = [
            't'      => $this->response->getToken(),
            'links'  => $this->response->getLinks(),
            'finish' => $this->response->finish,
            'quest'  => $this->response->getQuest(),
            'point'  => $this->response->getPoint(),
            'coords' => [
                'lat' => $this->response->getPosition()->getCoordinate()->getLatitude(),
                'lng' => $this->response->getPosition()->getCoordinate()->getLongitude(),
            ],
        ];
        $data['total_points'] = $data['quest']['total_points'];

        if ($this->response->getError() instanceof Error && $this->response->getError()->isStatus()) {
            $data['error'] = [
                'msg'  => $this->response->getError()->getMsg(),
                'type' => $this->response->getError()->getType(),
            ];

            $headers = [
                'Access-Control-Allow-Methods' => 'OPTIONS, GET, POST',
                'Access-Control-Allow-Origin'  => '*',
            ];

            $status = JsonResponse::HTTP_BAD_REQUEST;
        }

        return new JsonResponse($data, $status, $headers);
    }
}
