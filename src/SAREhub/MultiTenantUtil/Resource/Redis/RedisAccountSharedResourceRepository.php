<?php


namespace SAREhub\MultiTenantUtil\Resource\Redis;


use SAREhub\MultiTenantUtil\Resource\AccountSharedResourceInfo;
use SAREhub\MultiTenantUtil\Resource\ResourceInfo;

class RedisAccountSharedResourceRepository extends RedisResourceRepository
{
    protected function serializeResource(ResourceInfo $resource): array
    {
        return $resource->toArray();
    }

    protected function deserializeResource(array $data)
    {
        return new AccountSharedResourceInfo($data["id"], $data["sharedResourceId"]);
    }

    public function getResourceTypeName(): string
    {
        return "AccountSharedResource";
    }
}