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

use Kubikvest\Model\User;
use Kubikvest\Resource\Point\Model\Point;
use Kubikvest\Resource\Position\Model\Position;
use Kubikvest\Resource\Quest\Model\Quest;
use Silex\Application;

class Response
{
    /**
     * @var array
     */
    protected $links = [];
    public    $finish = false;
    protected $token;
    public    $error = false;
    protected $quest;
    protected $point;
    protected $position;
    /**
     * @var User
     */
    private $user;
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app   = $app;
        $this->token = $this->app['link.gen']->getToken($this->app['user']);
    }

    /**
     * @return array
     */
    public function getQuest()
    {
        return $this->quest;
    }

    /**
     * @param Quest $quest
     */
    public function setQuest(Quest $quest)
    {
        $this->quest = [
            'questId'      => $quest->getQuestId()->getValue(),
            'quest_id'     => $quest->getQuestId()->getValue(),
            'title'        => $quest->title,
            'description'  => $quest->description,
            'picture'      => $quest->picture,
            'points'       => $quest->points,
            'total_points' => count($quest->points),
        ];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return array
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * @param string $type
     */
    public function addLink($type)
    {
        $this->links[$type] = $this->app['link.gen']->getLink($type, $this->app['user']);
    }

    /**
     * @param Point $point
     */
    public function setPoint(Point $point)
    {
        $this->point = [
            'pointId'     => $point->getPointId()->getValue(),
            'point_id'    => $point->getPointId()->getValue(),
            'title'       => $point->getTitle(),
            'description' => $point->getDescription(),
            'coords'      => [
                'latitude'  => [
                    $point->getSector()->getLatitudeRange()->getMin(),
                    $point->getSector()->getLatitudeRange()->getMax(),
                ],
                'longitude' => [
                    $point->getSector()->getLongitudeRange()->getMin(),
                    $point->getSector()->getLongitudeRange()->getMax(),
                ]
            ]
        ];
    }

    /**
     * @return mixed
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @return Position
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param Position $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }
}
