<?php


namespace SAREhub\MultiTenantUtil\Resource;


interface ResourceRepository
{
    public function insert(ResourceInfo $resource);

    /**
     * @param string $id
     * @return ResourceInfo
     * @throws ResourceNotFoundException
     */
    public function find(string $id): ResourceInfo;

    /**
     * @return ResourceInfo[]
     */
    public function findAll(): array;

    public function remove(ResourceInfo $resource);
}