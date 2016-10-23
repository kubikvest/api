<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Firebase\JWT\JWT;
use Kubikvest\Model;
use Kubikvest\Subscriber\RequestSubscriber;

require_once __DIR__ . '/../vendor/autoload.php';

$config = require_once __DIR__ . '/../config/app.php';
$quest  = require_once __DIR__ . '/../config/quest.php';
$app    = new Application(array_merge_recursive($config, $quest));
$app['dispatcher']->addSubscriber(new RequestSubscriber($app));

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
    $user = $app['user.manager']->getUserByProviderCreds($data['user_id'], 'vk');

    if ($user->isEmpty()) {
        if (222 == $code) {
            $user = $app['user.manager']->createOnlyTest($data['user_id'], 'vk', $data['access_token'], $data['expires_in']);
        } else {
            $user = $app['user.manager']->create($data['user_id'], 'vk', $data['access_token'], $data['expires_in']);
        }
    } else {
        $user->accessToken = $data['access_token'];
        $user->ttl         = $data['expires_in'];
        $app['user.manager']->update($user);
    }

    if ($user->isEmptyGroup()) {
        $links['list_quest'] = $app['link.gen']->getLink(Model\LinkGenerator::LIST_QUEST, $user);
    } else {
        $links['task'] = $app['link.gen']->getLink(Model\LinkGenerator::TASK, $user);
    }

    return new JsonResponse([
        't'     => $app['link.gen']->getToken($user),
        'links' => $links,
    ]);
});

$app->get('/list-quest', function (Request $request) use ($app) {
    /**
     * @var \Kubikvest\Model\User $user
     */
    $user   = $app['user'];
    $quests = $app['quest.manager']->listQuest([]);

    $data = [];

    if ('8c5a3934-31b0-465e-812d-9a2e2074d0da' != $user->userId) {
        array_shift($quests);
    }

    foreach ($quests as $item) {
        /**
         * @var \Kubikvest\Model\Quest $item
         */
        $data[] = [
            'quest_id'    => $item->questId,
            'title'       => $item->title,
            'description' => $item->description,
            'link'        => $app['link.gen']->getLink(Model\LinkGenerator::CREATE_GAME, $user),
        ];
        if ('8c5a3934-31b0-465e-812d-9a2e2074d0da' == $user->userId) {
            break;
        }
    }

    return new JsonResponse([
        't'      => $app['link.gen']->getToken($user),
        'quests' => $data,
    ]);
});

$app->post('/create-game', function (Request $request) use ($app) {
    $data = $app['request.content'];

    /**
     * @var \Kubikvest\Model\User  $user
     * @var \Kubikvest\Model\Quest $quest
     * @var \Kubikvest\Model\Group $group
     */
    $user  = $app['user'];
    $quest = $app['quest.mapper']->getQuest($data['quest_id']);
    if (empty($user->groupId)){
        $group = $app['group.manager']->create($quest);
        $group->addUser($user);
        $user->groupId = $group->groupId;
        $app['user.manager']->update($user);
    }

    return new JsonResponse([
        't'     => $app['link.gen']->getToken($user),
        'links' => [
            'task' => $app['link.gen']->getLink(Model\LinkGenerator::TASK, $user),
        ],
    ]);
});

$app->get('/task', function (Request $request) use ($app) {
    /**
     * @var \Kubikvest\Model\User  $user
     * @var \Kubikvest\Model\Quest $quest
     * @var \Kubikvest\Model\Group $group
     * @var \Kubikvest\Model\Point $point
     */
    $user  = $app['user'];
    $group = $app['group.manager']->get($user->groupId);
    $quest = $app['quest.mapper']->getQuest($group->questId);
    $point = $app['point.mapper']->getPoint($group->pointId);

    if (null === $group->startPoint) {
        $group->startPoint = date('Y-m-d H:i:s');
        $app['group.manager']->update($group);
    }

    $response = [
        'quest' => (array) $quest,
        'point' => (array) $point,
        'timer' => $point->getTimer($group->startPoint),
        't'     => $app['link.gen']->getToken($user),
        'links' => [
            'checkpoint' => $app['link.gen']->getLink(Model\LinkGenerator::CHECKPOINT, $user),
        ],
        'total_points' => count($quest->points),
    ];
    $response['point']['prompt'] = $point->getPrompt($group->startPoint);

    return new JsonResponse($response);
});

$app->post('/checkpoint', function (Request $request) use ($app) {
    $data = $app['request.content'];

    /**
     * @var \Kubikvest\Model\User  $user
     * @var \Kubikvest\Model\Group $group
     * @var \Kubikvest\Model\Quest $quest
     * @var \Kubikvest\Model\Point $point
     */
    $user  = $app['user'];
    $group = $app['group.manager']->get($user->groupId);
    $quest = $app['quest.mapper']->getQuest($group->questId);
    $point = $app['point.mapper']->getPoint($group->pointId);

    $response = [
        't'            => $app['link.gen']->getToken($user),
        'quest'        => (array) $quest,
        'point'        => (array) $point,
        'total_points' => count($quest->points),
        'finish'       => false,
    ];
    unset($response['point']['prompt']);
    $app['logger']->log(
        \Psr\Log\LogLevel::INFO,
        'Checkout',
        [
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'acr' => $data['acr'],
        ]
    );
    if (! $point->checkCoordinates((double) $data['lat'], (double) $data['lng'])) {
        $distances = $point->calcDistanceToPointsSector((double) $data['lat'], (double) $data['lng']);
        if (! $point->checkAccuracy((int) $data['acr'], min($distances))) {
            $response['links']['checkpoint'] = $app['link.gen']
                ->getLink(Model\LinkGenerator::CHECKPOINT, $user);
            $response['error'] = 'Не верное место отметки.';

            return new JsonResponse($response, JsonResponse::HTTP_OK);
        }
    }

    $group->startPoint = null;

    if ($group->pointId == end($quest->points)) {
        $response['links']['finish'] = $app['link.gen']->getLink(Model\LinkGenerator::FINISH, $user);
        $group->pointId = null;
        $app['group.manager']->update($group);
        $response['finish'] = true;
    } else {
        $group->pointId = $quest->nextPoint($group->pointId);
        $app['group.manager']->update($group);
        $response['links']['task'] = $app['link.gen']->getLink(Model\LinkGenerator::TASK, $user);
    }

    $response['coords'] = [
        'lat' => $data['lat'] . '(' . (double) $data['lat'] . ')',
        'lng' => $data['lng'] . '(' . (double) $data['lng'] . ')',
        'acr' => $data['acr'],
    ];

    return new JsonResponse($response, JsonResponse::HTTP_OK);
});

$app->get('/finish', function (Request $request) use ($app) {
    /**
     * @var \Kubikvest\Model\User  $user
     * @var \Kubikvest\Model\Group $group
     */
    $user = $app['user'];
    $group = $app['group.manager']->get($user->groupId);

    if (! $group->isEmpty()) {
        $group->active = false;
        $app['group.manager']->update($group);
    }

    $user->groupId = null;
    $app['user.manager']->update($user);

    return new JsonResponse([], JsonResponse::HTTP_OK);
});

if (true === (bool) getenv('TEST')) {
    return $app;
} else {
    $app->run();
}
