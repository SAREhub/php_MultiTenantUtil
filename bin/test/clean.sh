#!/usr/bin/env bash
set -e -u
source ./vendor/sarehub/dockerutil/bin/dockerutil
set -a
source ./bin/test/.env
set +a


dockerutil::clean_all_with_label $TESTENV_LABEL
