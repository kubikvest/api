<?php
/**
 * Copyright (C) 2016 iMega ltd Dmitry Gavriloff (email: info@imega.ru),
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

namespace Kubikvest\Subscriber;

use Kubikvest\Model\Uuid;
use Kubikvest\Resource\User\Builder;
use Kubikvest\Resource\User\Model\User;
use Silex\Application;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Firebase\JWT\JWT;

/**
 * Class RequestSubscriber
 */
class RequestSubscriber implements EventSubscriberInterface
{
    /**
     * @var Application
     */
    private $app;
    /**
     * @param Application $app Application.
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }
    /**
     * Event on kernel
     *
     * @param GetResponseEvent $event Returns the request the kernel.
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $token   = null;
        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $this->app['request.content'] = $data;
            $token = $data['t'];
        } elseif ($request->isMethod('GET')) {
            $token = $request->get('t', null);
        }

        if (null !== $token) {
            $data = JWT::decode($token, $this->app['key'], ['HS256']);
            $this->app['user'] = $this->app['user.manager']->getUser($data->user_id);
            $this->app[User::class] = (new Builder($this->app))->build(new Uuid($data->user_id));
        }
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => array('onKernelRequest', 2048),
        ];
    }
}
