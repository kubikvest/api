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
use Kubikvest\Validator;
use Silex\Application;
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
     * @return Resource\Respondent
     */
    public function handle(Request $request)
    {
        /**
         * @var Resource\User\Model\User  $user
         * @var Resource\Position\Creator $creator
         * @var Resource\Group\Builder    $groupBuilder
         * @var Resource\Quest\Builder    $questBuilder
         * @var Resource\Point\Builder    $pointBuilder
         */
        $user         = $this->app[Resource\User\Model\User::class];
        $creator      = $this->app[Resource\Position\Creator::class];
        $groupBuilder = $this->app[Resource\Group\Builder::class];
        $questBuilder = $this->app[Resource\Quest\Builder::class];
        $pointBuilder = $this->app[Resource\Point\Builder::class];

        $response = new Resource\Checkpoint\Response($this->app);
        try {
            $position = $creator->create();
            $group    = $groupBuilder->build($user->getGroupId());
            $quest    = $questBuilder->build($group->getQuestId());
            $point    = $pointBuilder->build($group->getPointId());
        } catch (\Exception $e) {
            $response->setError(new Resource\Error(true, $e->getMessage()));
            return new Resource\Checkpoint\Respondent($response);
        }

        $response->setPosition($position);
        $response->setPoint($point);
        $response->setQuest($quest);

        $this->app['logger']->log(
            \Psr\Log\LogLevel::INFO,
            'Текущая точка',
            [
                'Id'  => $point->getPointId()->getValue(),
                'Lat' => $point->getSector()->getLatitudeRange()->getMin() . " - " . $point->getSector()
                        ->getLatitudeRange()->getMax(),
                'Lng' => $point->getSector()->getLongitudeRange()->getMin() . " - " . $point->getSector()
                        ->getLongitudeRange()->getMax(),
            ]
        );

        $this->app['logger']->log(
            \Psr\Log\LogLevel::INFO,
            'Расстояния до точек',
            [
                'в секторе'  => (new Validator\PositionInsideSector($position, $point->getSector()))->validate(),
                'точность' => (new Validator\PointIncludedAccuracyRange($position))->validate(),
                'точность меньше до ближнего угла' => (new Validator\AccuracyLessDistance($position, $point->getSector()))->validate(),
                'около сектора'  => (new Validator\PositionAroundBorderSector(
                    $position,
                    $point->getSector())
                )->validate(),
                'расстояния до углов'     => array_keys((new Validator\PositionAroundBorderSector(
                    $position, $point->getSector()
                ))->calcDistancesToPointsSector($position, $point->getSector())),
            ]
        );

        if (!$group->active) {
            $response->finish = true;
            $response->addLink(Model\LinkGenerator::FINISH);
            return new Resource\Checkpoint\Respondent($response);
        }

        $validator = new Validator\PlayerAtRightPlace(
            new Validator\PositionInsideSector($position, $point->getSector()),
            new Validator\PointIncludedAccuracyRange($position),
            new Validator\AccuracyLessDistance($position, $point->getSector()),
            new Validator\PositionAroundBorderSector($position, $point->getSector())
        );

        $req = $this->app['request.content'];
        if (isset($req['point_id']) && $req['point_id'] != $point->getPointId()->getValue()) {
            $this->app['logger']->log(\Psr\Log\LogLevel::CRITICAL, 'Повторный запрос', []);
            if ($group->active) {
                $response->finish = false;
                $response->addLink(Model\LinkGenerator::TASK);
            } else {
                $response->finish = true;
                $response->addLink(Model\LinkGenerator::FINISH);
            }

            return new Resource\Checkpoint\Respondent($response);
        }

        if (!$validator->validate()) {
            if ((new Validator\PointIncludedAccuracyRange($position))->validate()) {
                $response->setError(new Resource\Error(true, 'Не верное место отметки.', 'alert'));
                $this->app['logger']->log(
                    \Psr\Log\LogLevel::CRITICAL,
                    'Не верное место отметки.',
                    [
                        'name' => $user->getProvider()->name,
                        'id' => $user->getProvider()->uid,
                    ]
                );
            } else {
                $response->setError(new Resource\Error(true, 'Большая погрешность GPS ' . $position->getAccuracy(), 'alert'));
                $this->app['logger']->log(
                    \Psr\Log\LogLevel::CRITICAL,
                    'Большая погрешность GPS ' . $position->getAccuracy(),
                    [
                        'name' => $user->getProvider()->name,
                        'id' => $user->getProvider()->uid,
                    ]
                );
            }
            $response->addLink(Model\LinkGenerator::CHECKPOINT);

            return new Resource\Checkpoint\Respondent($response);
        }

        /**
         * @var Resource\Group\Updater $groupUpdater
         */
        $groupUpdater = $this->app[Resource\Group\Updater::class];

        if ((new Validator\PlayerFinished($group, $quest))->validate()) {
            $group->active = false;
            $groupUpdater->update($group);
            $response->finish = true;
            $response->addLink(Model\LinkGenerator::FINISH);
            $this->app['logger']->log(
                \Psr\Log\LogLevel::NOTICE,
                'Игрок финишировал.',
                [
                    'name' => $user->getProvider()->name,
                    'id' => $user->getProvider()->uid,
                ]
            );
        } else {
            $group->setPointId((new Resource\Group\NextPoint())->nextPoint($quest, $point));
            $group->setStartPoint(new \DateTime());
            $groupUpdater->update($group);
            $response->addLink(Model\LinkGenerator::TASK);
        }

        $this->app['logger']->log(
            \Psr\Log\LogLevel::NOTICE,
            'Пройдена точка',
            [
                'finish' => $response->finish,
                'name'   => $user->getProvider()->name,
                'id'     => $user->getProvider()->uid,
            ]
        );

        return new Resource\Checkpoint\Respondent($response);
    }
}
