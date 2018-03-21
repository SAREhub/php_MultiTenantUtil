test_init: test_clean test_init_redis

test_init_redis:
	bash bin/test/redis/init.sh

test_clean:
	bash bin/test/clean.sh



