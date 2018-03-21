<?php

namespace SAREhub\MultiTenantUtil\Redis;


use PHPUnit\Framework\TestCase;
use Predis\Client;
use SAREhub\MultiTenantUtil\DatabaseHost;
use SAREhub\MultiTenantUtil\DatabaseHostNotFoundException;

class RedisDatabaseHostRepositoryITest extends TestCase
{

    /**
     * @var Client
     */
    private $redisClient;

    /**
     * @var RedisDatabaseHostRepository
     */
    private $repository;

    protected function setUp()
    {
        $this->redisClient = new Client([
            "host" => "localhost",
            "port" => 10000,
            "schema" => "tcp",
            "read_write_timeout" => 10
        ]);

        $this->repository = new RedisDatabaseHostRepository($this->redisClient, "test_prefix");
        $this->redisClient->flushall();
    }

    protected function tearDown()
    {
        $this->redisClient->flushall();
    }

    public function testFindWhenExists()
    {
        $host = $this->saveHostInRepository();
        $this->assertEquals($host, $this->repository->find($host->getId()));
    }

    public function testFindWhenNotExists()
    {
        $hostId = "test_id";
        $this->expectException(DatabaseHostNotFoundException::class);
        $this->expectExceptionMessage("host '$hostId' not found");
        $this->repository->find($hostId);
    }

    public function testFindAll()
    {
        $host = $this->saveHostInRepository();
        $this->assertEquals([$host], $this->repository->findAll());
    }

    public function testRemoveWhenExists()
    {
        $host = $this->saveHostInRepository();
        $this->repository->remove($host);
        $this->assertEquals([], $this->repository->findAll());
    }

    public function testRemoveWhenNotExists()
    {
        $host = new DatabaseHost("test_id", "test_host", 1);
        $this->repository->remove($host);
        $this->assertEquals([], $this->repository->findAll());
    }

    private function saveHostInRepository(): DatabaseHost
    {
        $host = new DatabaseHost("test_id", "test_host", 1);
        $host->setCurrentAccounts(10);
        $host->setMaxAccounts(20);
        $this->repository->save($host);
        return $host;
    }
}
