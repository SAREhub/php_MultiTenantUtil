<?php


namespace SAREhub\MultiTenantUtil\Resource;


interface ResourceInfoRepository
{
    public function insert(ResourceInfo $resource);

    /**
     * @param string $id
     * @return ResourceInfo
     * @throws NotFoundResourceInfoException
     */
    public function find(string $id): ResourceInfo;

    /**
     * @return ResourceInfo[]
     */
    public function findAll(): array;

    public function remove(ResourceInfo $resource);
}