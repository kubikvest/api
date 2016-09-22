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

    EXPECTED='{"links":{"task":"http:\/\/kubikvest.xyz\/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM"}}'

    ACTUAL=$(curl --silent http://$URL/auth?code=222)

    assertTrue "$EXPECTED" "$ACTUAL" "$FUNCNAME body"
}

testStartTask() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    BODY=$(curl --silent "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM")

    DESCRIPTION=$(echo $BODY | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$DESCRIPTION" "$FUNCNAME DESCRIPTION"

    TOTAL_POINTS=$(echo $BODY | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    CHECKPOINT=$(echo $BODY | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"
}

testFailLocationCheckpoint() {
    ACTUAL=$(curl --silent "http://$URL/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM&c=200,200")

    QUEST_DESCRIPTION=$(echo $ACTUAL | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    TOTAL_POINTS=$(echo $ACTUAL | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    FINISH=$(echo $ACTUAL | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "false" "$FINISH" "$FUNCNAME FINISH"

    POINT_DESCRIPTION=$(echo $ACTUAL | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    CHECKPOINT=$(echo $ACTUAL | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"

    ERROR=$(echo $ACTUAL | jq '.error' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Не верное место отметки." "$ERROR" "$FUNCNAME ERROR"
}

testCheckpoint() {
    ACTUAL=$(curl --silent "http://$URL/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6IjE2YTRmOWRmLWU2MzYtNGNmYy1hZTMyLTkxMGMwYTIwYmEwMyJ9.uyy2xryrZjc0I5qdaplRc1Sdu1tbApwihtSFjIo2YBM&c=10,10")

    QUEST_TITLE=$(echo $ACTUAL | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $ACTUAL | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    TOTAL_POINTS=$(echo $ACTUAL | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    FINISH=$(echo $ACTUAL | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "false" "$FINISH" "$FUNCNAME FINISH"

    POINT_TITLE=$(echo $ACTUAL | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title start point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $ACTUAL | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point start description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $ACTUAL | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "null" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    TASK=$(echo $ACTUAL | jq '.links.task' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImQ3ZTlmNDMzLTdmMjEtNDdmMC1iMzIyLWI4ZWY0YWYwMzExMyJ9.GuKe3ZRrBoYivw50q9CovFq3Ob3wB-1Wu11398mTkDI" "$TASK" "$FUNCNAME TASK"
}

testFirstTask() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImQ3ZTlmNDMzLTdmMjEtNDdmMC1iMzIyLWI4ZWY0YWYwMzExMyJ9.GuKe3ZRrBoYivw50q9CovFq3Ob3wB-1Wu11398mTkDI")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    ACTUAL=$(curl --silent "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImQ3ZTlmNDMzLTdmMjEtNDdmMC1iMzIyLWI4ZWY0YWYwMzExMyJ9.GuKe3ZRrBoYivw50q9CovFq3Ob3wB-1Wu11398mTkDI")

    QUEST_TITLE=$(echo $ACTUAL | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $ACTUAL | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    TOTAL_POINTS=$(echo $ACTUAL | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    POINT_ID=$(echo $ACTUAL | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "d7e9f433-7f21-47f0-b322-b8ef4af03113" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $ACTUAL | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title first point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $ACTUAL | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point first description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $ACTUAL | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    CHECKPOINT=$(echo $ACTUAL | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImQ3ZTlmNDMzLTdmMjEtNDdmMC1iMzIyLWI4ZWY0YWYwMzExMyJ9.GuKe3ZRrBoYivw50q9CovFq3Ob3wB-1Wu11398mTkDI" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"
}

testFirstTaskCheckpoint() {
    ACTUAL=$(curl --silent "http://$URL/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImQ3ZTlmNDMzLTdmMjEtNDdmMC1iMzIyLWI4ZWY0YWYwMzExMyJ9.GuKe3ZRrBoYivw50q9CovFq3Ob3wB-1Wu11398mTkDI&c=10,10")

    QUEST_TITLE=$(echo $ACTUAL | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $ACTUAL | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    TOTAL_POINTS=$(echo $ACTUAL | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    FINISH=$(echo $ACTUAL | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "false" "$FINISH" "$FUNCNAME FINISH"

    POINT_TITLE=$(echo $ACTUAL | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title first point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $ACTUAL | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point first description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $ACTUAL | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "null" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    TASK=$(echo $ACTUAL | jq '.links.task' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImRkYzNlYzJlLTlkMTEtNGMyNi05NmZmLTYyMDc4OGFmOWUzNyJ9.HYT5KoUkUAsv7-8iUzoSB-3Xaj7cjCJSjvS0LHIJeto" "$TASK" "$FUNCNAME TASK"
}

testSecondTask() {
    ACTUAL=$(curl --write-out %{http_code} --silent --output /dev/null "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImRkYzNlYzJlLTlkMTEtNGMyNi05NmZmLTYyMDc4OGFmOWUzNyJ9.HYT5KoUkUAsv7-8iUzoSB-3Xaj7cjCJSjvS0LHIJeto")

    assertTrue 200 $ACTUAL "$FUNCNAME Code"

    ACTUAL=$(curl --silent "http://$URL/task?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImRkYzNlYzJlLTlkMTEtNGMyNi05NmZmLTYyMDc4OGFmOWUzNyJ9.HYT5KoUkUAsv7-8iUzoSB-3Xaj7cjCJSjvS0LHIJeto")

    QUEST_TITLE=$(echo $ACTUAL | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $ACTUAL | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    TOTAL_POINTS=$(echo $ACTUAL | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    POINT_ID=$(echo $ACTUAL | jq '.point.pointId' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "ddc3ec2e-9d11-4c26-96ff-620788af9e37" "$POINT_ID" "$FUNCNAME POINT_ID"

    POINT_TITLE=$(echo $ACTUAL | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title second point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $ACTUAL | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point second description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $ACTUAL | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    CHECKPOINT=$(echo $ACTUAL | jq '.links.checkpoint' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImRkYzNlYzJlLTlkMTEtNGMyNi05NmZmLTYyMDc4OGFmOWUzNyJ9.HYT5KoUkUAsv7-8iUzoSB-3Xaj7cjCJSjvS0LHIJeto" "$CHECKPOINT" "$FUNCNAME CHECKPOINT"
}

testSecondTaskCheckpoint() {
    ACTUAL=$(curl --silent "http://$URL/checkpoint?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImRkYzNlYzJlLTlkMTEtNGMyNi05NmZmLTYyMDc4OGFmOWUzNyJ9.HYT5KoUkUAsv7-8iUzoSB-3Xaj7cjCJSjvS0LHIJeto&c=10,10")

    QUEST_TITLE=$(echo $ACTUAL | jq '.quest.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Task Zero" "$QUEST_TITLE" "$FUNCNAME QUEST_TITLE"

    QUEST_DESCRIPTION=$(echo $ACTUAL | jq '.quest.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test task zero description" "$QUEST_DESCRIPTION" "$FUNCNAME QUEST_DESCRIPTION"

    TOTAL_POINTS=$(echo $ACTUAL | jq '.total_points' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "3" "$TOTAL_POINTS" "$FUNCNAME TOTAL_POINTS"

    FINISH=$(echo $ACTUAL | jq '.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "true" "$FINISH" "$FUNCNAME FINISH"

    POINT_TITLE=$(echo $ACTUAL | jq '.point.title' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test title second point" "$POINT_TITLE" "$FUNCNAME POINT_TITLE"

    POINT_DESCRIPTION=$(echo $ACTUAL | jq '.point.description' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "Test point second description" "$POINT_DESCRIPTION" "$FUNCNAME POINT_DESCRIPTION"

    POINT_PROMPT=$(echo $ACTUAL | jq '.point.prompt' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "null" "$POINT_PROMPT" "$FUNCNAME POINT_PROMPT"

    FINISH=$(echo $ACTUAL | jq '.links.finish' | sed -e 's/^"//' -e 's/"$//')
    assertTrue "http://kubikvest.xyz/finish?t=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhdXRoX3Byb3ZpZGVyIjoidmsiLCJ1c2VyX2lkIjo2Njc0OCwidHRsIjo0MzIwMCwicXVlc3RfaWQiOiJkOWIxMzVkMy05YTI5LTQ1ZjAtODc0Mi03Y2E2Zjk5ZDliNzMiLCJwb2ludF9pZCI6ImRkYzNlYzJlLTlkMTEtNGMyNi05NmZmLTYyMDc4OGFmOWUzNyJ9.HYT5KoUkUAsv7-8iUzoSB-3Xaj7cjCJSjvS0LHIJeto" "$FINISH" "$FUNCNAME TASK"
}

testAuth

testStartTask
testFailLocationCheckpoint
testCheckpoint

testFirstTask
testFirstTaskCheckpoint

testSecondTask
testSecondTaskCheckpoint

printf '%.0s-' {1..80}
echo
printf 'Total test: %s, fail: %s\n\n' "$COUNT_TESTS" "$COUNT_TESTS_FAIL"

if [ $COUNT_TESTS_FAIL -gt 0 ]; then
    exit 1
fi

exit 0
