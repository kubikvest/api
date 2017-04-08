#!/usr/bin/env bash

printf '%.0s-' {1..80}
echo

URL=$1

COUNT_TESTS=0
COUNT_TESTS_FAIL=0

assertTrue() {
    testName="$3"
    pad=$(printf '%0.1s' "."{1..80})
    padlength=78

    if [ "$1" != "$2" ]; then
        printf ' %s%*.*s%s' "$3" 0 $((padlength - ${#testName} - 4)) "$pad" "Fail"
        printf ' (expected %s, assertion %s)\n' "$1" "$2"
        let "COUNT_TESTS_FAIL++"
    else
        printf ' %s%*.*s%s\n' "$3" 0 $((padlength - ${#testName} - 2)) "$pad" "Ok"
        let "COUNT_TESTS++"
    fi
}

testAuth() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null http://$URL/auth?code=222)

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl --silent http://$URL/auth?code=222)

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    TASK=$(echo $BODY | jq '.links.list_quest' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/list-quest?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TASK" "$FUNCNAME TASK"
}

testListQuest() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/list-quest?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl --silent "http://$URL/list-quest?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    QUEST_ID=$(echo $BODY | jq '.quests[0].quest_id' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quests[0].title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quests[0].description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_LINK=$(echo $BODY | jq '.quests[0].link' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/create-game?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$QUEST_LINK" "$FUNCNAME QUEST_LINK"
}

testCreateGame() {
    ACTUAL=$(curl -X POST --write-out %{http_code} --silent --output /dev/null -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "quest_id":"d9b135d3-9a29-45f0-8742-7ca6f99d9b73"}' "http://$URL/create-game")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl -X POST --silent -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "quest_id":"d9b135d3-9a29-45f0-8742-7ca6f99d9b73"}' "http://$URL/create-game")

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    TASK=$(echo $BODY | jq '.links.task' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TASK" "$FUNCNAME TASK"
}

testStartTask() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl --silent "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_ID=$(echo $BODY | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title start point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    CHECKPOINT=$(echo $BODY | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"
}

testFailLocation42MCheckpoint() {
    ACTUAL=$(curl -X POST --write-out %{http_code} --silent --output /dev/null -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "lat":60.983626, "lng":25.658256, "acr":39}' http://$URL/checkpoint)

    assertTrue 400 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl -X POST --silent -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "lat":60.983626, "lng":25.658256, "acr":39}' http://$URL/checkpoint)

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_ID=$(echo $BODY | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title start point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    CHECKPOINT=$(echo $BODY | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"

    FINISH=$(echo $BODY | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "false" "$FINISH" "$FUNCNAME FINISH"

    ERROR=$(echo $BODY | jq '.error.msg' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Не верное место отметки." "$ERROR" "$FUNCNAME ERROR"
}

testFailACRCheckpoint() {
    ACTUAL=$(curl -X POST --write-out %{http_code} --silent --output /dev/null -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "lat":60.983726, "lng":25.658856, "acr":42}' http://$URL/checkpoint)

    assertTrue 400 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl -X POST --silent -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "lat":60.983726, "lng":25.658856, "acr":42}' http://$URL/checkpoint)

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_ID=$(echo $BODY | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title start point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    CHECKPOINT=$(echo $BODY | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"

    FINISH=$(echo $BODY | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "false" "$FINISH" "$FUNCNAME FINISH"

    ERROR=$(echo $BODY | jq '.error.msg' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Не верное место отметки." "$ERROR" "$FUNCNAME ERROR"
}

testLocationInSectorCheckpoint() {
    BODY=$(curl --silent -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "lat":60.983858, "lng":25.659040, "acr":39}' http://$URL/checkpoint)

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_ID=$(echo $BODY | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title start point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $BODY | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "null" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    TASK=$(echo $BODY | jq '.links.task' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TASK" "$FUNCNAME TASK"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    FINISH=$(echo $BODY | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "false" "$FINISH" "$FUNCNAME FINISH"
}

testFirstTask() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl --silent "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_ID=$(echo $BODY | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title first point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point first description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    #POINT_PROMPT=$(echo $BODY | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    #assertTrue "" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    CHECKPOINT=$(echo $BODY | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"
}

testFirstTaskTriangleCheckpoint() {
    BODY=$(curl --silent -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "lat":60.983766, "lng":25.659005, "acr":39}' http://$URL/checkpoint)

    TOKEN=$(echo $BODY | jq '.t' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TOKEN" "$FUNCNAME TOKEN"

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_ID=$(echo $BODY | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title first point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point first description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $BODY | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "null" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    TASK=$(echo $BODY | jq '.links.task' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$TASK" "$FUNCNAME TASK"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    FINISH=$(echo $BODY | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "false" "$FINISH" "$FUNCNAME FINISH"
}

testSecondTask() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl --silent "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_ID=$(echo $BODY | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title second point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point second description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    #POINT_PROMPT=$(echo $BODY | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    #assertTrue "" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    CHECKPOINT=$(echo $BODY | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"
}

testSecondTaskLessDistanceCheckpoint() {
    BODY=$(curl --silent -d '{"t":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4", "lat":60.983766, "lng":25.659195, "acr":39}' http://$URL/checkpoint)

    QUEST_ID=$(echo $BODY | jq '.quest.questId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d9b135d3-9a29-45f0-8742-7ca6f99d9b73" "$QUEST_ID" "$FUNCNAME QUEST_ID"

    QUEST_TITLE=$(echo $BODY | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $BODY | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[0]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "16a4f9df-e636-4cfc-ae32-910c0a20ba03" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 1"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[1]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 2"

    QUEST_POINTS=$(echo $BODY | jq '.quest.points[2]' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$QUEST_POINTS" "$FUNCNAME QUEST_POINT 3"

    POINT_TITLE=$(echo $BODY | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title second point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point second description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $BODY | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "null" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    FINISH=$(echo $BODY | jq '.links.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/finish?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4" "$FINISH" "$FUNCNAME TASK"

    FINISH=$(echo $BODY | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "true" "$FINISH" "$FUNCNAME FINISH"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"
}

testFinish() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/finish?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")
    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl --silent "http://$URL/finish?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoiOGM1YTM5MzQtMzFiMC00NjVlLTgxMmQtOWEyZTIwNzRkMGRhIn0.KO8wMlYcfdX4tAZWF7eegaOmX6l1BdrayUYYolAu3v4")
}

testAuth

testListQuest

testCreateGame


testStartTask
testFailLocation42MCheckpoint
testFailACRCheckpoint
testLocationInSectorCheckpoint

testFirstTask
testFirstTaskTriangleCheckpoint

testSecondTask
testSecondTaskLessDistanceCheckpoint

testFinish

printf '%.0s-' {1..80}
echo
printf 'Total test: %s, fail: %s\n\n' "$COUNT_TESTS" "$COUNT_TESTS_FAIL"

if [ $COUNT_TESTS_FAIL -gt 0 ]; then
    exit 1
fi

exit 0
