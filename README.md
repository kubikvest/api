# Api
[![CircleCI](https://circleci.com/gh/kubikvest/api/tree/master.svg?style=svg)](https://circleci.com/gh/kubikvest/api/tree/master) [![Travis-ci Build Status](https://travis-ci.org/kubikvest/api.svg?branch=master)](https://travis-ci.org/kubikvest/api) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/kubikvest/api/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/kubikvest/api/?branch=master) [![Scrutinizer Code Coverage](https://scrutinizer-ci.com/g/kubikvest/api/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/kubikvest/api/?branch=master) [![Scrutinizer Build Status](https://scrutinizer-ci.com/g/kubikvest/api/badges/build.png?b=master)](https://scrutinizer-ci.com/g/kubikvest/api/build-status/master)

## API

Авторизация пользователя
```
ACTION: auth
METHOD: GET
QUERY: code
---
{
  "t":string,
  "links": [
    {"list_quest": "string"}
  ]
}
```

Получения списка квестов
```
ACTION: list-quest
METHOD: GET
QUERY: t
---
{
  "t":string,
  "quests": [
    {
      "quest_id": "string",
      "title": "string",
      "description": "string",
      "link": "string",
    }
  ]
}
```

Создание игры
```
ACTION: create-game
METHOD: POST
TYPE: application/json
BODY: t, quest_id
```

Получения текущего задания
```
ACTION: task
METHOD: GET
QUERY: t
```

Отметка точки
```
ACTION: checkpont
METHOD: POST
TYPE: application/json
BODY: t, lat, lon
---
{
  "t":"string",
  "quest":{
    "questId":"string",
    "title":"string",
    "description":"string",
    "points":[
      "string"
    ]
  },
  "point":{
    "pointId":"string",
    "title":"string",
    "description":"string"
  },
  "total_points": int,
  "finish": bool,
  "links":{
    "string":"string"
  }
}
```

Завершение игры
```
ACTION: finish
METHOD: POST
TYPE: application/json
BODY: t
```

## License
