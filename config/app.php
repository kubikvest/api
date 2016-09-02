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
        0 => [
            [
                'kvest'       => 0,
                'point'       => 0,
                'title'       => 'Test title',
                'description' => 'Test description',
                'coords' => [
                    'latitude'  => [0, 100],
                    'longitude' => [0, 100],
                ],
                'prompt' => [
                    20 => 'Test first prompt',
                    40 => 'Test secont prompt',
                    60 => 'Test answer',
                ],
            ],
        ],
        1 => [
            [
                'kvest'       => 1,
                'point'       => 0,
                'title' => 'Задание 0',
                'description' => 'Вам нужно подойти на Софийскую площадь к памятнику В.И. Ленину',
                'coords' => [
                    'latitude'  => [0, 100],
                    'longitude' => [0, 100],
                ],
                'prompt' => [
                    20 => 'Первая подсказка',
                    40 => 'Вторая подсказка',
                    60 => 'Ответ',
                ],
            ],
            [
                'kvest'       => 1,
                'point'       => 1,
                'title' => 'Задание 1',
                'description' => 'Если провести линию от самой северной башни кремля через башню которая изображена в центре пятирублевой купюры, то мы найдем место где земля оголяет историю.',
                'coords' => [
                    'latitude' => [0, 100],
                    'longitude' => [0, 100],
                ],
                'prompt' => [
                    20 => 'Именно со Спасской башни открывается прекрасный вид на Людин конец, где в наше время любят покопаться.',
                    40 => 'Тут все троица, и улица, и церковь и этот объект.',
                    60 => 'Троицкий раскоп, что рядом с ц.Святой Троицы, на Троицкой улице. (Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.) ',
                ],
            ],
            [
                'kvest'       => 1,
                'point'       => 2,
                'title' => 'Задание 2',
                'description' => 'Пройдите 428 метра на юго-восток, от перекрестка где вы находитесь, до цилиндрического объекта.',
                'coords' => [
                    'latitude'  => [0, 100],
                    'longitude' => [0, 100],
                ],
                'prompt' => [
                    20 => ' “А башня-то круглая!” - сказал Алексей.',
                    40 => 'Белый подтвердил: “И вид с этой башни отличный, на водную гладь и вал.”',
                    60 => 'Белая Башня, она же Алексеевская, что по улице Троицкой (Пробойной) возле лодочной станции.(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.)',
                ],
            ],
            [
                'kvest'       => 1,
                'point'       => 3,
                'title' => 'Задание 3',
                'description' => 'Вдоль по валу окольного города можно набрести на монастырь с разрушенным собором.',
                'coords' => [
                    'latitude'  => [0, 100],
                    'longitude' => [0, 100],
                ],
                'prompt' => [
                    20 => 'Монастырь, а монахов-то и след простыл, одни картины.',
                    40 => 'Монастырь сей назвали старой русской единицей земельной площади.',
                    60 => 'Десятинный монастырь, а разрушенный собор на его территории, это собор Рождества Богородицы.(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.)',
                ],
            ],
            [
                'kvest'       => 1,
                'point'       => 4,
                'title' => 'Задание 4',
                'description' => 'Добро пожаловать в самую пропасть Загородского конца!',
                'coords' => [
                    'latitude'  => [0, 100],
                    'longitude' => [0, 100],
                ],
                'prompt' => [
                    20 => 'Лишь одна древняя постройка сохранилась в этом конце.',
                    40 => '12 церковных апостолов взирают на бассейн.',
                    60 => 'Церковь Двенадцати Апостолов на Пропасте́х, рядом с бассейном.(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.).',
                ],
            ],
            [
                'kvest'       => 1,
                'point'       => 5,
                'title' => 'Задание 5 (последнее)',
                'description' => 'А теперь, сделайте сэлфи у музыкального памятника. (ставь хештег #kubikvest)',
                'coords' => [
                    'latitude'  => [0, 100],
                    'longitude' => [0, 100],
                ],
                'prompt' => [
                    20 => 'Открытие этого памятника было приурочено к 1150-летию Великого Новгорода.',
                    40 => 'Не думай об огромной скамейке!)',
                    60 => 'Памятник композитору Сергею Рахманинову.',
                ],
            ],
        ],
    ],
    'checkCoordinates' => function (Application $app) {
        return function ($kvestId, $pointId, $latitude, $longitude) use ($app) {
            $rangePosition = $app['tasks'][$kvestId][$pointId]['coords'];
            return $rangePosition['latitude'][MIN] <= $latitude &&
                $latitude <= $rangePosition['latitude'][MAX] &&
                $rangePosition['longitude'][MIN] <= $longitude &&
                $longitude <= $rangePosition['longitude'][MAX];
        };
    }
];
