<?php
return [
    'quest' => [
        /**
         * Для автотестов
         */
        'd9b135d3-9a29-45f0-8742-7ca6f99d9b73' => [
            'title' => 'Task Zero',
            'description' => 'Test task zero description',
            'picture' => '',
            'points' => [
                '16a4f9df-e636-4cfc-ae32-910c0a20ba03',
                'd7e9f433-7f21-47f0-b322-b8ef4af03113',
                'ddc3ec2e-9d11-4c26-96ff-620788af9e37',
            ],
        ],
        /**
         * Живые квесты
         */
        '74184cd8-b02a-4505-9416-b136468bfeaf' => [
            'title' => 'Светлый путь',
            'description' => '<b>Сложность-средняя</b>/Возрастная категория-14+/Протяженность-около 4-х км/Работает-круглосуточно',
            'picture' => '/img/87e86364-c943-11e6-81fe-f25e4f1164c8.jpeg',
            'points' => [
                '1d2d4455-bb0a-48f0-8970-63e4db6bc52c',
                'c0614188-b320-43b7-9f5d-91f65249be0f',
                'e4e37339-b783-4f20-8eca-fb498366e496',
                '05132aee-a9fc-447d-bef6-4798e4f31bcf',
                'c477f2c1-9368-4560-aaa6-19876a9acb6e',
                '1bdf7da9-a290-405e-8d4d-3d43bac4f9d2',
            ],
        ],
        'e8034e86-d8c2-430f-ac6f-99876aae74e9' => [
            'title' => 'Ой, все!',
            'description' => 'Сложность-легкая/Возрастная категория-7+/Протяженность-около 3-х км/Работает-09:00-23:00',
            'picture' => '/img/87e9c43e-c943-11e6-9198-34e6ace4a05d.jpeg',
            'points' => [
                'ee62fcc0-d116-45a0-a942-d6a585e36c4b',
                '01b1dfbb-0e8e-4879-89ec-e5d88fc88ce8',
                '34508a1d-7a76-4363-8bb7-e9aaaffc8275',
                '8fee48f5-ef57-48b8-a02c-daa17ce9e0f9',
                'd9f83782-17d9-4d74-ad81-b5da732eacb2',
                '6f3c2252-0f00-42ae-971c-718b35a21a1d',
            ],
        ],
        'd53c9c0c-75dc-4816-862f-20913b1cdd49' => [
            'title' => 'Черный плащ',
            'description' => 'Сложность-легкая/Возрастная категория-7+/Протяженность-около 2,5 км/Работает-09:00-21:00',
            'picture' => '/img/87e9d780-c943-11e6-9702-6dcb8dd92772.jpeg',
            'points' => [
                '746505de-b53d-4ba6-9f49-db9eed34324b',
                '4d2402d7-7699-436b-a12a-bd40ebcbc7e6',
                '4fd0bf6b-659c-4d9b-b446-306b68513ea9',
                'f8658947-0341-4059-9692-45b4c6167f91',
                'de9f4fb0-6501-4a8d-a750-8df7e106fed0',
                '00cf856c-e259-405a-9c01-4345f4310168',
            ],
        ],
        'd53c9c0c-75dc-4816-862f-20913b1cdd19' => [
            'title' => 'Тестовый',
            'description' => 'только для тестов -  не нажимай!',
            'picture' => '/img/87e9d780-c943-11e6-9702-6dcb8dd92772.jpeg',
            'points' => [
                '746505de-b53d-4ba6-9f49-db9eed343242',
                '4d2402d7-7699-436b-a12a-bd40ebcbc7e2',
                '4fd0bf6b-659c-4d9b-b446-306b68513ea2',
                'f8658947-0341-4059-9692-45b4c6167f92',
                'de9f4fb0-6501-4a8d-a750-8df7e106fed2',
                '00cf856c-e259-405a-9c01-4345f4310162',
            ],
        ],
    ],
    'points' => [
        '1d2d4455-bb0a-48f0-8970-63e4db6bc52c' => [
            'title'       => 'Старт',
            'description' => 'Чтобы начать игру вам нужно подойти на Софийскую площадь к памятнику В.И. Ленину.',
            'coords' => [
                'latitude'  => [58.5220, 58.5238],
                'longitude' => [31.2699, 31.2715],
            ],
            'prompt' => [],
        ],
        'c0614188-b320-43b7-9f5d-91f65249be0f' => [
            'title' => 'Задание №1',
            'description' => 'Если провести линию от самой северной башни кремля через башню которая изображена в центре пятирублевой купюры, то мы найдем место где земля оголяет историю.',
            'coords' => [
                'latitude' => [58.5157, 58.5170],
                'longitude' => [31.2718, 31.2738],
            ],
            'prompt' => [
                20 => 'Именно со Спасской башни открывается прекрасный вид на Людин конец, где в наше время любят покопаться.',
                40 => 'Тут все троица, и улица, и церковь и этот объект.',
                60 => 'Троицкий раскоп, что рядом с ц.Святой Троицы, на Троицкой улице. (Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.) ',
            ],
        ],
        'e4e37339-b783-4f20-8eca-fb498366e496' => [
            'title' => 'Задание №2',
            'description' => 'Пройдите 428 метра на юго-восток, от перекрестка где вы находитесь, до цилиндрического объекта.',
            'coords' => [
                'latitude'  => [58.5135, 58.5148],
                'longitude' => [31.2700, 31.2713],
            ],
            'prompt' => [
                20 => '“А башня-то круглая!” - сказал Алексей.',
                40 => 'Белый подтвердил: “И вид с этой башни отличный, на водную гладь и вал.”',
                60 => 'Белая Башня, она же Алексеевская, что по улице Троицкой (Пробойной) возле лодочной станции.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '05132aee-a9fc-447d-bef6-4798e4f31bcf' => [
            'title' => 'Задание №3',
            'description' => 'Вдоль по валу окольного города можно набрести на монастырь с разрушенным собором.',
            'coords' => [
                'latitude'  => [58.5170, 58.5186],
                'longitude' => [31.2650, 31.2659],
            ],
            'prompt' => [
                20 => 'Монастырь, а монахов-то и след простыл, одни картины.',
                40 => 'Монастырь сей назвали старой русской единицей земельной площади.',
                60 => 'Десятинный монастырь, а разрушенный собор на его территории, это собор Рождества Богородицы.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        'c477f2c1-9368-4560-aaa6-19876a9acb6e' => [
            'title' => 'Задание №4',
            'description' => 'Добро пожаловать в самую пропасть Загородского конца!',
            'coords' => [
                'latitude'  => [58.5213, 58.5220],
                'longitude' => [31.2628, 31.2640],
            ],
            'prompt' => [
                20 => 'Лишь одна древняя постройка сохранилась в этом конце.',
                40 => '12 церковных апостолов взирают на бассейн.',
                60 => 'Церковь Двенадцати Апостолов на Пропасте́х, рядом с бассейном.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.).',
            ],
        ],
        '1bdf7da9-a290-405e-8d4d-3d43bac4f9d2' => [
            'title' => 'Задание №5 (последнее)',
            'description' => 'А теперь, сделайте сэлфи у музыкального памятника. (ставьте хештег #kubikvest к фото)',
            'coords' => [
                'latitude'  => [58.5337, 58.5245],
                'longitude' => [31.2710, 31.2723],
            ],
            'prompt' => [
                20 => 'Открытие этого памятника было приурочено к 1150-летию Великого Новгорода.',
                40 => 'Не думай об огромной скамейке!)',
                60 => 'Памятник композитору Сергею Рахманинову.',
            ],
        ],
        /**
         * Второй квест
         */
        'ee62fcc0-d116-45a0-a942-d6a585e36c4b' => [
            'title'       => 'Старт',
            'description' => 'Чтобы начать игру вам нужно подойти к Ганзейскому Фонтану на Ярославом дворище.',
            'coords' => [
                'latitude'  => [58.5188, 58.5208],
                'longitude' => [31.2828, 31.2879],
            ],
            'prompt' => [],
        ],
        '01b1dfbb-0e8e-4879-89ec-e5d88fc88ce8' => [
            'title' => 'Задание №1',
            'description' => 'Сразу за горбатой переправой есть одинокий резной пенек.',
            'coords' => [
                'latitude'  => [58.5209, 58.5232],
                'longitude' => [31.2829, 31.2861],
            ],
            'prompt' => [
                20 => 'Это так же излюбленное место новгородских моржей.',
                40 => 'Ищите деревянную фигуру между мостом и кафе "На причале", кстати, летом там зазывают на водные прогулки.',
                60 => 'Пенек от дерева превратили в небольшую скульптурку, по правой стороне от моста. Там же зимой купаются моржи. а летом отправляются в прогулки на кораблях.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '34508a1d-7a76-4363-8bb7-e9aaaffc8275' => [
            'title' => 'Задание №2',
            'description' => 'В самой северной части Кремля имеется "лихое" здание с резной лестницей.',
            'coords' => [
                'latitude'  => [58.5225, 58.5239],
                'longitude' => [31.2758, 31.2768],
            ],
            'prompt' => [
                20 => 'Это тихий уголок возле Федоровской башни и Грановитой палаты.',
                40 => 'За Софийским собором братья Лихуды основали свое училище, а ныне там проводят выставки.',
                60 => 'Лихудов корпус, что за Софийским собором, красивое здание с живописной деревянной лестницей.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '8fee48f5-ef57-48b8-a02c-daa17ce9e0f9' => [
            'title' => 'Задание №3',
            'description' => 'Найдите легендарное место со вкуснейшим мороженным из детства.',
            'coords' => [
                'latitude'  => [58.5174, 58.5185],
                'longitude' => [31.2700, 31.2710],
            ],
            'prompt' => [
                20 => 'Рядом с этим сказочным местом слышны звуки аттракционов.',
                40 => 'Еще тут можно попить вкусный чай с лимоном и заказать пиццу с восточными сладостями.',
                60 => 'Кафе-чайхона "Сказка", там же пиццерия "Лимончелло" по ул. Большая Власьевская, 1.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        'd9f83782-17d9-4d74-ad81-b5da732eacb2' => [
            'title' => 'Задание №4',
            'description' => 'На лево, посмотришь стену увидишь, на право посмотришь воду узришь, а стоишь ты в песке аки гриб.',
            'coords' => [
                'latitude'  => [58.5236, 58.5238],
                'longitude' => [31.2865, 31.2867],
            ],
            'prompt' => [
                20 => 'Отсчитайте 7й "грибочек" от "коня".',
                40 => 'Встаньте прямо по середине между "конем" и мостом!',
                60 => 'Зона в центре пляжа, по дороге от Монумента Победы (кстати это единственный конный монумент в городе), что на Екатерининской горке до Кремлевского моста.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '6f3c2252-0f00-42ae-971c-718b35a21a1d' => [
            'title' => 'Задание №5',
            'description' => 'Теперь вам предстоит найти райскую птицу с головой девы. Не бойтесь, она не улетит, заперта в клетке навеки!',
            'coords' => [
                'latitude'  => [58.5230, 58.5238],
                'longitude' => [31.2850, 31.2868],
            ],
            'prompt' => [
                20 => 'По пути к нему вы встретите уставшую туристку, корабль, и даже рисующего мальчика.',
                40 => 'На ручье Федора есть большой магазин, вот как раз подле него и сидит в клетке легендарная сладкоголосая птица, в славянской мифологии поющая прекрасные песни о будущем.',
                60 => 'Райская птица Сирин - скульпутра рядом с универмагом "Диез" и мостом Александра Невского.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        /**
         * Третий квест
         */
        '746505de-b53d-4ba6-9f49-db9eed34324b' => [
            'title'       => 'Старт',
            'description' => 'Чтобы начать игру вам нужно подойти  к памятнику Александру Невскому на одноименной набережной, что рядом с Борисоглебской церковью.',
            'coords' => [
                'latitude'  => [58.5292, 58.5293],
                'longitude' => [31.2835, 31.2842],
            ],
            'prompt' => [],
        ],
        '4d2402d7-7699-436b-a12a-bd40ebcbc7e6' => [
            'title' => 'Задание №1',
            'description' => 'Примерно в радиусе 300 метров от вас стоит человек из металла на ходулях и охраняет вход.',
            'coords' => [
                'latitude'  => [58.5286, 58.5294],
                'longitude' => [31.2939, 31.2942],
            ],
            'prompt' => [
                20 => 'Можно долго вести диалог об этом человеке, однако лучше его увидеть. https://goo.gl/sh26uN',
                40 => 'Оказывается человек этот не охраняет, а приглашает всех на выставки в полукруглое здание.',
                60 => 'МАУК Центр культуры, искусств и общественных инициатив Диалог, у входа есть металлическа скульптура человека на ходулях. Кстати, вы можете посетить любую выставку со скидкой. Просто скажите что вы от Кубика и покажите задание не телефоне.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '4fd0bf6b-659c-4d9b-b446-306b68513ea9' => [
            'title' => 'Задание №2',
            'description' => 'Нашлось такое вот определение - это самый крупный вид семейства оленевых. Найдите его.',
            'coords' => [
                'latitude'  => [58.5331, 58.5340],
                'longitude' => [31.2938, 31.2972],
            ],
            'prompt' => [
                20 => 'Около 35-ти лет он стоял без рогов, но сейчас все на месте.',
                40 => 'Сохатый всегда ждет вас в парке 30-летия Октября, рядом периодически болеют за "Тосно".',
                60 => 'Памятник Лосю работы неизвестного скульптора у стадиона "Центральный" (бывш. "Электрон") в парке 30-летия Октября.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        'f8658947-0341-4059-9692-45b4c6167f91' => [
            'title' => 'Задание №3',
            'description' => 'Теперь определите стороны света, вам нужно пройти около 280 шагов на северо - восток.',
            'coords' => [
                'latitude'  => [58.5345, 58.5356],
                'longitude' => [31.2944, 31.2982],
            ],
            'prompt' => [
                20 => 'Прошли? Молодцы! Наверняка где-то рядом виднеется сказочный замок и слышны голоса детей.',
                40 => 'Вокруг этой детской площадки целых три места для перекуса: Шкипер, Наполи и La Chatte.',
                60 => 'Большая детская площадка в виде замка в парке 30-летия Октября, рядом со стадионом "Центральный".(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.)',
            ],
        ],
        'de9f4fb0-6501-4a8d-a750-8df7e106fed0' => [
            'title' => 'Найдите еще одну десткую площадку за https://goo.gl/5gPxDn',
            'description' => '004',
            'coords' => [
                'latitude'  => [58.5365, 58.5366],
                'longitude' => [31.2914, 31.2917],
            ],
            'prompt' => [
                20 => 'За четырехзвездочным паласом, она и находится.',
                40 => 'Из окон ресторана "На солнце" очень хорошо видна эта небольшая и тихая детская площадка.',
                60 => 'Небольшая детская площадка за гостинницей "Парк Инн", совсем рядом расположился ресторан "На солнце".(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '00cf856c-e259-405a-9c01-4345f4310168' => [
            'title' => 'Задание №5',
            'description' => 'Теперь посмотрим как вы читаете карты. https://goo.gl/5yRxq4',
            'coords' => [
                'latitude'  => [58.5400, 58.5408],
                'longitude' => [31.2881, 31.2886],
            ],
            'prompt' => [
                20 => 'На карте изображен древнейший новгородский монастырь на улице парков.',
                40 => 'Тут же расположен и университет, но нам нужен именно собор.',
                60 => 'Собор Рождества Богородицы, на территорри Антониева монастыря на Парковой улице.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        /**
         * 4 квест
         */
        '746505de-b53d-4ba6-9f49-db9eed343242' => [
            'title'       => 'Старт',
            'description' => 'стартовое место',
            'coords' => [
                'latitude'  => [58.5335, 58.5346],
                'longitude' => [31.2662, 31.2684],
            ],
            'prompt' => [],
        ],
        '4d2402d7-7699-436b-a12a-bd40ebcbc7e2' => [
            'title' => 'Задание №1',
            'description' => '1 задание',
            'coords' => [
                'latitude'  => [58.5325, 58.5335],
                'longitude' => [31.2664, 31.2683],
            ],
            'prompt' => [
                20 => 'Можно долго вести диалог об этом человеке, однако лучше его увидеть. https://goo.gl/sh26uN',
                40 => 'Оказывается человек этот не охраняет, а приглашает всех на выставки в полукруглое здание.',
                60 => 'МАУК Центр культуры, искусств и общественных инициатив Диалог, у входа есть металлическа скульптура человека на ходулях. Кстати, вы можете посетить любую выставку со скидкой. Просто скажите что вы от Кубика и покажите задание не телефоне.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '4fd0bf6b-659c-4d9b-b446-306b68513ea2' => [
            'title' => 'Задание №2',
            'description' => '2 задание',
            'coords' => [
                'latitude'  => [58.5301, 58.5309],
                'longitude' => [31.2695, 31.2709],
            ],
            'prompt' => [
                20 => 'Около 35-ти лет он стоял без рогов, но сейчас все на месте.',
                40 => 'Сохатый всегда ждет вас в парке 30-летия Октября, рядом периодически болеют за "Тосно".',
                60 => 'Памятник Лосю работы неизвестного скульптора у стадиона "Центральный" (бывш. "Электрон") в парке 30-летия Октября.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        'f8658947-0341-4059-9692-45b4c6167f92' => [
            'title' => 'Задание №3',
            'description' => '3 задание',
            'coords' => [
                'latitude'  => [58.5349, 58.5357],
                'longitude' => [31.2665, 31.2679],
            ],
            'prompt' => [
                20 => 'Прошли? Молодцы! Наверняка где-то рядом виднеется сказочный замок и слышны голоса детей.',
                40 => 'Вокруг этой детской площадки целых три места для перекуса: Шкипер, Наполи и La Chatte.',
                60 => 'Большая детская площадка в виде замка в парке 30-летия Октября, рядом со стадионом "Центральный".(Подойдите к этому объекту и еще раз нажмите "проверить ответ", чтобы получить следующее задание.)',
            ],
        ],
        'de9f4fb0-6501-4a8d-a750-8df7e106fed2' => [
            'title' => '4 задание https://goo.gl/5gPxDn',
            'description' => '004',
            'coords' => [
                'latitude'  => [58.5406, 58.5414],
                'longitude' => [31.2242, 31.2257],
            ],
            'prompt' => [
                20 => 'За четырехзвездочным паласом, она и находится.',
                40 => 'Из окон ресторана "На солнце" очень хорошо видна эта небольшая и тихая детская площадка.',
                60 => 'Небольшая детская площадка за гостинницей "Парк Инн", совсем рядом расположился ресторан "На солнце".(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        '00cf856c-e259-405a-9c01-4345f4310162' => [
            'title' => 'Задание №5',
            'description' => '5 задание https://goo.gl/5yRxq4',
            'coords' => [
                'latitude'  => [58.5417, 58.5426],
                'longitude' => [31.2210, 31.2225],
            ],
            'prompt' => [
                20 => 'На карте изображен древнейший новгородский монастырь на улице парков.',
                40 => 'Тут же расположен и университет, но нам нужен именно собор.',
                60 => 'Собор Рождества Богородицы, на территорри Антониева монастыря на Парковой улице.(Подойдите к этому объекту и еще раз нажмите "проверить место", чтобы получить следующее задание.)',
            ],
        ],
        /**
         * SPB
         */
        'ab95b999-6b02-45b2-a4c1-ac7098201d36' => [
            'title'       => 'First point',
            'description' => '',
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
        '17120974-ce95-4ac0-b34a-1097c1806fe6' => [
            'title'       => 'Second point',
            'description' => '',
            'coords' => [
                'latitude'  => [60.0059, 60.0062],
                'longitude' => [30.4026, 30.4034],
            ],
            'prompt' => [
                20 => 'Test first prompt',
                40 => 'Test secont prompt',
                60 => 'Test answer',
            ],
        ],
        'a69da8fa-30e2-4513-bb8f-4c8ebf03982e' => [
            'title'       => 'Finish point',
            'description' => '',
            'coords' => [
                'latitude'  => [60.0065, 60.0068],
                'longitude' => [30.4018, 30.4026],
            ],
            'prompt' => [
                20 => 'Test first prompt',
                40 => 'Test secont prompt',
                60 => 'Test answer',
            ],
        ],
        /**
         * Точки кветста для тестирования
         */
        '16a4f9df-e636-4cfc-ae32-910c0a20ba03' => [
            'title'       => 'Test title start point',
            'description' => 'Test point start description',
            'coords' => [
                'latitude'  => [60.983826, 60.983902],
                'longitude' => [25.658975, 25.659115],
            ],
            'prompt' => [],
        ],
        'd7e9f433-7f21-47f0-b322-b8ef4af03113' => [
            'title'       => 'Test title first point',
            'description' => 'Test point first description',
            'coords' => [
                'latitude'  => [60.983826, 60.983902],
                'longitude' => [25.658975, 25.659115],
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
                'latitude'  => [60.983826, 60.983902],
                'longitude' => [25.658975, 25.659115],
            ],
            'prompt' => [
                20 => 'Test first prompt',
                40 => 'Test secont prompt',
                60 => 'Test answer',
            ],
        ],
    ],
];
