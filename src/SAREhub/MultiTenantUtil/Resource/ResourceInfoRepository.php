<?php


namespace SAREhub\MultiTenantUtil\Resource;


interface ResourceInfoRepository
{
    /**
     * @param ResourceInfo $resource
     * @throws ResourceInfoExistsException When resource with same id exists
     */
    public function insert(ResourceInfo $resource);

    /**
     * @param ResourceInfo $resource
     */
    public function replace(ResourceInfo $resource);

    /**
     * @param string $id
     * @return bool
     */
    public function exists(string $id): bool;

    /**
     * @param string $id
     * @return ResourceInfo
     * @throws ResourceInfoNotFoundException When resource with id not exists
     */
    public function find(string $id): ResourceInfo;

    /**
     * @return ResourceInfo[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     */
    public function delete(string $id);
}