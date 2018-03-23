<?php

namespace SAREhub\MultiTenantUtil;


use PHPUnit\Framework\TestCase;
use Predis\Client;

class RedisTestCase extends TestCase
{
    /**
     * @var Client
     */
    protected $redisClient;

    protected function setUp()
    {
        $this->redisClient = new Client([
            "host" => "localhost",
            "port" => 10000,
            "schema" => "tcp",
            "read_write_timeout" => 10
        ]);
        $this->redisClient->flushall();
    }

    protected function tearDown()
    {
        $this->redisClient->flushall();
    }
}