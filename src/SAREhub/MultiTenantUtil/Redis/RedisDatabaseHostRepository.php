<?php

namespace SAREhub\MultiTenantUtil\Redis;


use Predis\Client;
use Predis\Collection\Iterator\Keyspace;
use SAREhub\MultiTenantUtil\DatabaseHost;
use SAREhub\MultiTenantUtil\DatabaseHostNotFoundException;
use SAREhub\MultiTenantUtil\DatabaseHostRepository;

class RedisDatabaseHostRepository implements DatabaseHostRepository
{
    const KEY_FORMAT = "%s:%s";

    /**
     * @var Client
     */
    private $redisClient;

    /**
     * @var $keyPrefix
     */
    private $keyPrefix;

    public function __construct(Client $redisClient, string $keyPrefix)
    {
        $this->redisClient = $redisClient;
        $this->keyPrefix = $keyPrefix;
    }

    public function save(DatabaseHost $host)
    {
        $this->redisClient->hmset($this->getPrefixedKey($host->getId()), $host->toArray());
    }

    public function find(string $id): DatabaseHost
    {
        $data = $this->redisClient->hgetall($this->getPrefixedKey($id));
        if (empty($data)) {
            throw new DatabaseHostNotFoundException("host '$id' not found");
        }

        return $this->createHostFromArray($data);
    }

    public function findAll(): array
    {
        $it = new Keyspace($this->redisClient, $this->getPrefixedKey("*"));
        $hosts = [];
        foreach ($it as $key) {
            $hosts[] = $this->createHostFromArray($this->redisClient->hgetall($key));
        }
        return $hosts;
    }

    public function remove(DatabaseHost $host)
    {
        $this->redisClient->del([$this->getPrefixedKey($host->getId())]);
    }

    private function getPrefixedKey(string $id): string
    {
        return sprintf(self::KEY_FORMAT, $this->keyPrefix, $id);
    }

    private function createHostFromArray($data): DatabaseHost
    {
        $host = new DatabaseHost($data["id"], $data["host"], $data["port"]);
        $host->setCurrentAccounts($data["currentAccounts"]);
        $host->setMaxAccounts($data["maxAccounts"]);
        return $host;
    }
}