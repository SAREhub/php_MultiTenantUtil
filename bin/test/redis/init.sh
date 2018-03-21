#!/usr/bin/env bash
set -e -u

source ./vendor/sarehub/dockerutil/bin/dockerutil
set -a
source ./bin/test/.env
set +a

docker service create \
    --name $REDIS_SERVICE \
    --publish $REDIS_PUBLISH_PORT:6379 \
    --hostname $REDIS_SERVICE \
    --limit-cpu 1 \
    --limit-memory 50M \
    --log-driver json-file \
    --log-opt max-size=5m \
    --log-opt max-file=1 \
    --detach=true \
    --label $TESTENV_LABEL \
    redis:3.2.11-alpine