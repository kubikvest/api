<?php
return [
    'quest' => [
        'd9b135d3-9a29-45f0-8742-7ca6f99d9b73' => [
            'title' => 'Task Zero',
            'description' => 'Test task zero description',
            'points' => [
                '16a4f9df-e636-4cfc-ae32-910c0a20ba03',
                'd7e9f433-7f21-47f0-b322-b8ef4af03113',
                'ddc3ec2e-9d11-4c26-96ff-620788af9e37',
            ],
        ],
        '74184cd8-b02a-4505-9416-b136468bfeaf' => [
            'title' => 'Окольный град',
            'description' => 'Тип квеста - пеший
                              Сложность - средняя
                              Возрастная категория - 14+ 
                              Протяженность - около 4-х км',
            'points' => [
                '1d2d4455-bb0a-48f0-8970-63e4db6bc52c',
                'c0614188-b320-43b7-9f5d-91f65249be0f',
                'e4e37339-b783-4f20-8eca-fb498366e496',
                '05132aee-a9fc-447d-bef6-4798e4f31bcf',
                'c477f2c1-9368-4560-aaa6-19876a9acb6e',
                '1bdf7da9-a290-405e-8d4d-3d43bac4f9d2',
            ],
        ],
    ],
    'points' => [
        '1d2d4455-bb0a-48f0-8970-63e4db6bc52c' => [
            'title'       => '',
            'description' => 'Вам нужно подойти на Софийскую площадь к памятнику В.И. Ленину',
            'coords' => [
                'latitude'  => [58.522673-58.523066],
                'longitude' => [31.270837-31.271504],
            ],
            'prompt' => [],
        ],
        'c0614188-b320-43b7-9f5d-91f65249be0f' => [
            'title' => 'Задание 1',
            'description' => 'Если провести линию от самой северной башни кремля через башню которая изображена в центре пятирублевой купюры, то мы найдем место где земля оголяет историю.',
            'coords' => [
                'latitude' => [58.515885-58.516617],
                'longitude' => [31.272149-31.273541],
            ],
            'prompt' => [
                20 => 'Именно со Спасской башни открывается прекрасный вид на Людин конец, где в наше время любят покопаться.',
                40 => 'Тут все троица, и улица, и церковь и этот объект.',
                60 => 'Троицкий раскоп, что рядом с ц.Святой Троицы, на Троицкой улице. (Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.) ',
            ],
        ],
        'e4e37339-b783-4f20-8eca-fb498366e496' => [
            'title' => 'Задание 2',
            'description' => 'Пройдите 428 метра на юго-восток, от перекрестка где вы находитесь, до цилиндрического объекта.',
            'coords' => [
                'latitude'  => [58.512285-58.512965],
                'longitude' => [31.268819-31.270038],
            ],
            'prompt' => [
                20 => ' “А башня-то круглая!” - сказал Алексей.',
                40 => 'Белый подтвердил: “И вид с этой башни отличный, на водную гладь и вал.”',
                60 => 'Белая Башня, она же Алексеевская, что по улице Троицкой (Пробойной) возле лодочной станции.(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.)',
            ],
        ],
        '05132aee-a9fc-447d-bef6-4798e4f31bcf' => [
            'title' => 'Задание 3',
            'description' => 'Вдоль по валу окольного города можно набрести на монастырь с разрушенным собором.',
            'coords' => [
                'latitude'  => [58.51735-58.518729],
                'longitude' => [31.262710-31.265808],
            ],
            'prompt' => [
                20 => 'Монастырь, а монахов-то и след простыл, одни картины.',
                40 => 'Монастырь сей назвали старой русской единицей земельной площади.',
                60 => 'Десятинный монастырь, а разрушенный собор на его территории, это собор Рождества Богородицы.(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.)',
            ],
        ],
        'c477f2c1-9368-4560-aaa6-19876a9acb6e' => [
            'title' => 'Задание 4',
            'description' => 'Добро пожаловать в самую пропасть Загородского конца!',
            'coords' => [
                'latitude'  => [58.521491-58.521935],
                'longitude' => [31.262605-31.263645],
            ],
            'prompt' => [
                20 => 'Лишь одна древняя постройка сохранилась в этом конце.',
                40 => '12 церковных апостолов взирают на бассейн.',
                60 => 'Церковь Двенадцати Апостолов на Пропасте́х, рядом с бассейном.(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.).',
            ],
        ],
        '1bdf7da9-a290-405e-8d4d-3d43bac4f9d2' => [
            'title' => 'Задание 5 (последнее)',
            'description' => 'А теперь, сделайте сэлфи у музыкального памятника. (ставь хештег #kubikvest)',
            'coords' => [
                'latitude'  => [58.524029-58.524346],
                'longitude' => [31.272242-31.272763],
            ],
            'prompt' => [
                20 => 'Открытие этого памятника было приурочено к 1150-летию Великого Новгорода.',
                40 => 'Не думай об огромной скамейке!)',
                60 => 'Памятник композитору Сергею Рахманинову.',
            ],
        ],
        /**
         * Точки кветста для тестирования
         */
        '16a4f9df-e636-4cfc-ae32-910c0a20ba03' => [
            'title'       => 'Test title start point',
            'description' => 'Test point start description',
            'coords' => [
                'latitude'  => [0, 100],
                'longitude' => [0, 100],
            ],
            'prompt' => [],
        ],
        'd7e9f433-7f21-47f0-b322-b8ef4af03113' => [
            'title'       => 'Test title first point',
            'description' => 'Test point first description',
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
        'ddc3ec2e-9d11-4c26-96ff-620788af9e37' => [
            'title'       => 'Test title second point',
            'description' => 'Test point second description',
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
];
