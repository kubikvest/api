<?php

use Silex\Application;
use GuzzleHttp\Client as GuzzleClient;

const MIN = 0;
const MAX = 1;

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
        return new \Kubikvest\Mapper\User($app['pdo'], $app['queryBuilder']);
    },
    'tasks' => [
        1 => [
            [
                'kvest'       => 1,
                'point'       => 0,
                'description' => 'Вы должны прийти сюда чтобы начать',
                'coords' => [
                    'latitude'  => [10, 20],
                    'longitude' => [30, 40],
                ],
            ],
            [
                'kvest'       => 1,
                'point'       => 1,
                'description' => 'description description 11',
                'coords' => [
                    'latitude'  => [10, 20],
                    'longitude' => [30, 40],
                ],
            ],
        ],
    ],
    'checkCoordinates' => function (Application $app) {
        return function ($kvestId, $pointId, $latitude, $longitude) use ($app) {
            $rangePosition = $app['task'][$kvestId][$pointId]['coords'];
            return $rangePosition['latitude'][MIN] <= $latitude &&
                $latitude <= $rangePosition['latitude'][MAX] &&
                $rangePosition['longitude'][MIN] <= $longitude &&
                $longitude <= $rangePosition['latitude'][MAX];
        };
    }
];
