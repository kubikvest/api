<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;
use Kubikvest\Model;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/app.php';
$quest  = require_once __DIR__ . '/../config/quest.php';
$app    = new Application(array_merge_recursive($config, $quest));

$app->get('/auth', function(Request $request) use ($app) {
    $code = $request->get('code');

    try {
        $response = $app['curl']->request('GET', '/access_token', [
            'query' => [
                'client_id'     => $app['client_id'],
                'client_secret' => $app['client_secret'],
                'redirect_uri'  => $app['redirect_uri'],
                'code'          => $code,
            ]
        ]);
    } catch (RuntimeException $e) {
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
    $user = $app['user.manager']->getUser($data['user_id']);

    if ($user->isEmpty()) {
        $questId = $code == 222 ? 'd9b135d3-9a29-45f0-8742-7ca6f99d9b73' : '74184cd8-b02a-4505-9416-b136468bfeaf';
        /**
         * @var \Kubikvest\Model\Quest $quest
         */
        $quest = $app['quest.mapper']->getQuest($questId);

        $user->provider    = 'vk';
        $user->userId      = $data['user_id'];
        $user->accessToken = $data['access_token'];
        $user->questId     = $questId;
        $user->pointId     = $quest->points[0];
        $app['user.manager']->newbie($user);
    } else {
        $user->accessToken = $data['access_token'];
        $app['user.manager']->update($user);
    }

    return new JsonResponse([
        'links' => [
            'task' => $app['link.gen']->getLink(Model\LinkGenerator::TASK, $user, $data['expires_in'], 'vk'),
        ]
    ]);
});

$app->get('/task', function (Request $request) use ($app) {
    $jwt = $request->get('t');

    try {
        $data = JWT::decode($jwt, $app['key'], ['HS256']);
    } catch(Exception $e) {
        return new JsonResponse(
            [
                'error' => $e->getMessage(),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @var \Kubikvest\Model\User  $user
     * @var \Kubikvest\Model\Quest $quest
     * @var \Kubikvest\Model\Point $point
     */
    $user  = $app['user.manager']->getUser($data->user_id);
    $quest = $app['quest.mapper']->getQuest($user->questId);
    $point = $app['point.mapper']->getPoint($user->pointId);

    if (null === $user->startTask) {
        $user->startTask = date('Y-m-d H:i:s');
        $app['user.mapper']->setStartTask($user);
    }

    $response = [
        'quest'        => (array) $quest,
        'point'        => (array) $point,
        'start_task'   => $user->startTask,
        'timer'        => $point->getTimer($user->startTask),
        'total_points' => count($quest->points),
        'links' => [
            'checkpoint' => $app['link.gen']->getLink(Model\LinkGenerator::CHECKPOINT, $user, $data->ttl, 'vk'),
        ],
    ];
    $response['point']['prompt'] = $point->getPrompt($user->startTask);

    return new JsonResponse($response, JsonResponse::HTTP_OK);
});

$app->get('/checkpoint', function (Request $request) use ($app) {
    $jwt    = $request->get('t');
    $coords = $request->get('c');
    list($lat, $lon) = explode(',', $coords);

    try {
        $data = JWT::decode($jwt, $app['key'], ['HS256']);
    } catch(Exception $e) {
        return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @var \Kubikvest\Model\User  $user
     * @var \Kubikvest\Model\Quest $quest
     * @var \Kubikvest\Model\Point $point
     */
    $user  = $app['user.manager']->getUser($data->user_id);
    $quest = $app['quest.mapper']->getQuest($user->questId);
    $point = $app['point.mapper']->getPoint($user->pointId);

    $response = [
        'quest'        => (array) $quest,
        'point'        => (array) $point,
        'total_points' => count($quest->points),
        'finish'       => false,
    ];
    unset($response['point']['prompt']);
    if (!$point->checkCoordinates((double) $lat, (double) $lon)) {
        $response['links']['checkpoint'] = $app['link.gen']
            ->getLink(Model\LinkGenerator::CHECKPOINT, $user, $data->ttl, 'vk');
        $response['error'] = 'Не верное место отметки.';

        return new JsonResponse($response, JsonResponse::HTTP_OK);
    }

    $user->pointId   = $quest->nextPoint($user->pointId);
    $user->startTask = null;
    $app['user.mapper']->update($user);

    if ($data->point_id == end($quest->points)) {
        $response['links']['finish'] = $app['link.gen']->getLink(Model\LinkGenerator::FINISH, $user, $data->ttl, 'vk');
        $user->pointId = null;
        $app['user.mapper']->update($user);
        $response['finish'] = true;
    } else {
        $response['links']['task'] = $app['link.gen']->getLink(Model\LinkGenerator::TASK, $user, $data->ttl, 'vk');
    }

    return new JsonResponse($response, JsonResponse::HTTP_OK);
});

$app->get('/finish', function (Request $request) use ($app) {
    $jwt = $request->get('t');

    try {
        $data = JWT::decode($jwt, $app['key'], ['HS256']);
    } catch(Exception $e) {
        return new JsonResponse(
            [
                'error' => $e->getMessage(),
            ],
            JsonResponse::HTTP_BAD_REQUEST
        );
    }

    /**
     * @var \Kubikvest\Model\User $user
     */
    $user = $app['user.manager']->getUser($data->user_id);

    return new JsonResponse(
        [
            'description'  => $app['tasks'][$user->questId][$user->pointId]['description'],
            'point_id'     => $user->pointId,
            'total_points' => count($app['tasks'][$user->questId]),
            'links' => [
                'checkpoint' => $app['url'] . '/checkpoint?t=' . JWT::encode(
                        [
                            'auth_provider' => 'vk',
                            'user_id'       => $user->userId,
                            'ttl'           => $data->ttl,
                            'kvest_id'      => $user->questId,
                            'point_id'      => $user->pointId,
                        ],
                        $app['key']
                    )
            ],
        ],
        JsonResponse::HTTP_OK
    );
});

if (true === (bool) getenv('TEST')) {
    return $app;
} else {
    $app->run();
}
