<?php

namespace SAREhub\MultiTenantUtil\Resource\Redis;

use SAREhub\MultiTenantUtil\RedisTestCase;
use SAREhub\MultiTenantUtil\Resource\AccountSharedResourceInfo;
use SAREhub\MultiTenantUtil\Resource\NotFoundResourceInfoException;
use SAREhub\MultiTenantUtil\Resource\ResourceInfo;

class RedisResourceInfoRepositoryITest extends RedisTestCase
{

    /**
     * @var RedisResourceInfoRepository
     */
    private $repository;

    protected function setUp()
    {
        parent::setUp();
        $this->repository = new RedisResourceInfoRepository($this->redisClient, "test_prefix", "test_resource_type");
    }

    public function testFindWhenExists()
    {
        $res = $this->insertResourceToRepository();
        $this->assertEquals($res, $this->repository->find($res->getId()));
    }

    public function testFindWhenNotExists()
    {
        $resId = "test_id";
        $this->expectException(NotFoundResourceInfoException::class);
        $this->expectExceptionMessage("Resource of type 'test_resource_type' and id 'test_id' not found");
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
        $this->repository->delete($res->getId());
        $this->assertEquals([], $this->repository->findAll());
    }

    public function testRemoveWhenNotExists()
    {
        $this->repository->delete("not_exists_id");
        $this->assertEquals([], $this->repository->findAll());
    }

    private function insertResourceToRepository(): ResourceInfo
    {
        $res = $this->createResource();
        $this->repository->insert($res);
        return $res;
    }

    private function createResource(): ResourceInfo
    {
        return new ResourceInfo("test_id", ["field" => "value"]);
    }
}
