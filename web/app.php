<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Kubikvest\Subscriber\RequestSubscriber;

$config = require_once __DIR__ . '/../config/app.php';
$quest  = require_once __DIR__ . '/../config/quest.php';
$app    = new Application(array_merge_recursive($config, $quest));
$app['dispatcher']->addSubscriber(new RequestSubscriber($app));

$app->register(new \Kubikvest\Resource\Position\Provider());
$app->register(new \Kubikvest\Resource\Point\Provider());
$app->register(new \Kubikvest\Resource\Group\Provider());
$app->register(new \Kubikvest\Resource\User\Provider());
$app->register(new \Kubikvest\Resource\Quest\Provider());

$app->get('/auth', function(Request $request) use ($app) {
    return (new \Kubikvest\Handler\Auth($app))->handle($request);
});

$app->get('/clean', function (Request $request) use ($app) {
    return (new \Kubikvest\Handler\Clean($app))->handle($request);
});

$app->get('/list-quest', function (Request $request) use ($app) {
    return (new \Kubikvest\Handler\ListQuest($app))->handle($request);
});

$app->post('/create-game', function (Request $request) use ($app) {
    return (new \Kubikvest\Handler\CreateGame($app))->handle($request);
});

$app->get('/task', function (Request $request) use ($app) {
    return (new \Kubikvest\Handler\Task($app))->handle($request);
});

$app->post('/checkpoint', function (Request $request) use ($app) {
    $data = $app['request.content'];
    $app['logger']->log(
        \Psr\Log\LogLevel::INFO,
        'Checkout',
        [
            'lat' => $data['lat'],
            'lng' => $data['lng'],
            'acr' => $data['acr'],
        ]
    );

    $respondent = (new \Kubikvest\Handler\Checkpoint($app))->handle($request);

    return $respondent->response();
});

$app->get('/finish', function (Request $request) use ($app) {
    return (new \Kubikvest\Handler\Finish($app))->handle($request);
});

$app->get('/escape', function (Request $request) use ($app) {
    return (new \Kubikvest\Handler\Escape($app))->handle($request);
});

if (true === (bool) getenv('TEST')) {
    return $app;
} else {
    $app->run();
}
