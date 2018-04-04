<?php

namespace SAREhub\MultiTenantUtil\Resource\Redis;

use SAREhub\MultiTenantUtil\RedisTestCase;
use SAREhub\MultiTenantUtil\Resource\AccountSharedResourceInfo;
use SAREhub\MultiTenantUtil\Resource\ResourceInfo;
use SAREhub\MultiTenantUtil\Resource\ResourceInfoExistsException;
use SAREhub\MultiTenantUtil\Resource\ResourceInfoNotFoundException;

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

    public function testInsertWhenExists()
    {
        $res = $this->createResource();
        $this->repository->insert($res);

        $this->expectException(ResourceInfoExistsException::class);
        $this->repository->insert($res);
    }

    public function testReplaceWhenNotExists()
    {
        $res = $this->createResource();
        $this->repository->replace($res);
        $this->assertEquals($res, $this->repository->find($res->getId()));
    }

    public function testReplaceWhenExistsThenReplaced()
    {
        $old = $this->createResource("test", ["field" => "initial"]);
        $this->repository->replace($old);

        $expected = $this->createResource("test", ["field" => "replaced"]);
        $this->repository->replace($expected);

        $this->assertEquals($expected, $this->repository->find($expected->getId()));
    }

    public function testExistsWhenExists()
    {
        $res = $this->insertResourceToRepository();
        $this->assertTrue($this->repository->exists($res->getId()));
    }

    public function testExistsWhenNotExists()
    {
        $this->assertFalse($this->repository->exists("not exists"));
    }

    public function testFindWhenExists()
    {
        $res = $this->insertResourceToRepository();
        $this->assertEquals($res, $this->repository->find($res->getId()));
    }

    public function testFindWhenNotExists()
    {
        $resId = "test_id";
        $this->expectException(ResourceInfoNotFoundException::class);
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

    private function createResource(string $idSuffix = "", array $fields = ["field" => "value"]): ResourceInfo
    {
        return new ResourceInfo("test_id" . $idSuffix, $fields);
    }
}
