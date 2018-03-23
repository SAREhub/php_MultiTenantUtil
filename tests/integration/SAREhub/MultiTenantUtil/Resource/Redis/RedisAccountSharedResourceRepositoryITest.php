<?php

namespace SAREhub\MultiTenantUtil\Resource\Redis;

use SAREhub\MultiTenantUtil\RedisTestCase;
use SAREhub\MultiTenantUtil\Resource\AccountSharedResourceInfo;
use SAREhub\MultiTenantUtil\Resource\ResourceNotFoundException;

class RedisAccountSharedResourceRepositoryITest extends RedisTestCase
{

    /**
     * @var RedisSharedResourceRepository
     */
    private $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->repository = new RedisAccountSharedResourceRepository($this->redisClient, "test_prefix");
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
        $this->expectExceptionMessage("Resource of type 'AccountSharedResource' and id 'test_id' not found");
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

    private function insertResourceToRepository(): AccountSharedResourceInfo
    {
        $res = $this->createResource();
        $this->repository->insert($res);
        return $res;
    }

    private function createResource(): AccountSharedResourceInfo
    {
        return new AccountSharedResourceInfo("test_id", "test_shared_resource_id");
    }
}
