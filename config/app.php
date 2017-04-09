<?php

use Silex\Application;
use GuzzleHttp\Client as GuzzleClient;

return [
    'debug' => true,
    'client_id'     => getenv('VK_CLIENT_ID'),
    'client_secret' => getenv('VK_CLIENT_SECRET'),
    'redirect_uri'  => getenv('VK_REDIRECT_URI'),
    'key'           => getenv('KEY'),
    'url'           => getenv('URL'),
    'curl' => function () {
        return new GuzzleClient([
            'base_uri'    => getenv('URI_OAUTH_VK'),
            'headers'     => ['content-type' => 'text/xml; charset=utf-8'],
            'http_errors' => false,
            'debug'       => false,
        ]);
    },
    'db.options' => function () {
        return [
            'driver'     => 'mysql',
            'host'       => 'kubikvest_db',
            'port'       => '3306',
            'dbuser'     => 'root',
            'dbpassword' => '',
            'dbname'     => 'kubikvest',
            'charset'    => "UTF8",
        ];
    },
    'pdo' => function (Application $app) {
        $charset = isset($app['db.options']["charset"]) ? $app['db.options']["charset"] : "utf8";
        $dsn = "{$app['db.options']['driver']}:host={$app['db.options']["host"]};dbname={$app['db.options']['dbname']};charset={$charset}";
        $pdo = new PDO($dsn, $app['db.options']['dbuser'], $app['db.options']['dbpassword']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    },
    'queryBuilder' => function () {
        return new \Packaged\QueryBuilder\Builder\QueryBuilder();
    },
    'user.mapper' => function (Application $app) {
        return new \Kubikvest\Mapper\UserMapper($app['pdo'], $app['queryBuilder']);
    },
    'user.manager' => function (Application $app) {
        return new \Kubikvest\Manager\UserManager($app['user.mapper']);
    },
    'quest.mapper' => function (Application $app) {
        return new \Kubikvest\Mapper\QuestMapper($app['quest']);
    },
    'quest.manager' => function (Application $app) {
        return new \Kubikvest\Manager\QuestManager($app['quest.mapper']);
    },
    'group.manager' => function (Application $app) {
        return new \Kubikvest\Manager\GroupManager(
            new \Kubikvest\Mapper\GroupMapper($app['pdo'], $app['queryBuilder'])
        );
    },
    'point.mapper' => function (Application $app) {
        return new \Kubikvest\Mapper\PointMapper($app['points']);
    },
    'point.manager' => function (Application $app) {
        return new \Kubikvest\Manager\PointManager($app['point.mapper']);
    },
    'link.gen' => function (Application $app) {
        return new \Kubikvest\Model\LinkGenerator($app['url'], $app['key']);
    },
    'checkCoordinates' => function (Application $app) {
        return function ($kvestId, $pointId, $latitude, $longitude) use ($app) {
            $rangePosition = $app['tasks'][$kvestId][$pointId]['coords'];
            return $rangePosition['latitude'][MIN] <= $latitude &&
                $latitude <= $rangePosition['latitude'][MAX] &&
                $rangePosition['longitude'][MIN] <= $longitude &&
                $longitude <= $rangePosition['longitude'][MAX];
        };
    },
    'logger' => function () {
        $logger = new Monolog\Logger('kubikvest');
        $logger->setHandlers([
            new Monolog\Handler\StreamHandler('php://stdout'),
        ]);

        return $logger;
    },
    \Kubikvest\Resource\Token::class => function (Application $app) {
        return new \Kubikvest\Resource\Token($app['key'], 86000);
    },
];
