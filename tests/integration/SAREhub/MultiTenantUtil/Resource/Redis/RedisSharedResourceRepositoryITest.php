<?php

namespace SAREhub\MultiTenantUtil\Resource\Redis;

use SAREhub\MultiTenantUtil\RedisTestCase;
use SAREhub\MultiTenantUtil\Resource\ResourceNotFoundException;
use SAREhub\MultiTenantUtil\Resource\SharedResourceInfo;

class RedisSharedResourceRepositoryITest extends RedisTestCase
{
    /**
     * @var RedisSharedResourceRepository
     */
    private $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->repository = new RedisSharedResourceRepository($this->redisClient, "test_prefix");
    }

    public function testFindWhenExists()
    {
        $res = $this->insertResourceToRepository();
        $this->assertEquals($res, $this->repository->find($res->getId()));
    }

    public function testFindWhenNotExists()
    {
        $resId = "test_id";
        $this->expectException(ResourceNotFoundException::class);
        $this->expectExceptionMessage("Resource of type 'SharedResource' and id 'test_id' not found");
        $this->repository->find($resId);
    }

    public function testFindAll()
    {
        $res = $this->insertResourceToRepository();
        $this->assertEquals([$res], $this->repository->findAll());
    }

    public function testRemoveWhenExists()
    {
        $res = $this->insertResourceToRepository();
        $this->repository->remove($res);
        $this->assertEquals([], $this->repository->findAll());
    }

    public function testRemoveWhenNotExists()
    {
        $host = $this->createResource();
        $this->repository->remove($host);
        $this->assertEquals([], $this->repository->findAll());
    }

    private function insertResourceToRepository(): SharedResourceInfo
    {
        $res = $this->createResource();
        $this->repository->insert($res);
        return $res;
    }

    private function createResource(): SharedResourceInfo
    {
        $res = new SharedResourceInfo("test_id", ["test" => 1]);
        $res->setCurrentAccounts(10);
        $res->setMaxAccounts(20);
        return $res;
    }
}
