<?php


namespace SAREhub\MultiTenantUtil;


interface DatabaseHostRepository
{
    public function save(DatabaseHost $host);

    /**
     * @param string $id
     * @return DatabaseHost
     * @throws DatabaseHostNotFoundException
     */
    public function find(string $id): DatabaseHost;

    public function findAll(): array;

    public function remove(DatabaseHost $host);
}