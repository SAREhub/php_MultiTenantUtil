<?php


namespace SAREhub\MultiTenantUtil\Resource\Redis;


use SAREhub\MultiTenantUtil\Resource\ResourceInfo;
use SAREhub\MultiTenantUtil\Resource\SharedResourceInfo;

class RedisSharedResourceRepository extends RedisResourceRepository
{

    protected function serializeResource(ResourceInfo $resource): array
    {
        $data = $resource->toArray();
        $data["attributes"] = json_encode($data["attributes"]);
        return $data;
    }

    protected function deserializeResource(array $data)
    {
        $res = new SharedResourceInfo($data["id"], json_decode($data["attributes"], true));
        $res->setCurrentAccounts($data["currentAccounts"]);
        $res->setMaxAccounts($data["maxAccounts"]);
        return $res;
    }

    public function getResourceTypeName(): string
    {
        return "SharedResource";
    }
}