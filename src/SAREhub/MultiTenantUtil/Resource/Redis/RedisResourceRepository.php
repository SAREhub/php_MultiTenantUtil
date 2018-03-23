<?php


namespace SAREhub\MultiTenantUtil\Resource\Redis;


use Predis\Client;
use Predis\Collection\Iterator\Keyspace;
use SAREhub\MultiTenantUtil\Resource\ResourceInfo;
use SAREhub\MultiTenantUtil\Resource\ResourceNotFoundException;
use SAREhub\MultiTenantUtil\Resource\ResourceRepository;

abstract class RedisResourceRepository implements ResourceRepository
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


    public function insert(ResourceInfo $resource)
    {
        $key = $this->getPrefixedKey($resource->getId());
        $data = $this->serializeResource($resource);
        $this->getRedisClient()->hmset($key, $data);
    }

    /**
     * @param string $id
     * @return ResourceInfo
     * @throws ResourceNotFoundException
     */
    public function find(string $id): ResourceInfo
    {
        $data = $this->getRedisClient()->hgetall($this->getPrefixedKey($id));
        if (empty($data)) {
            throw new ResourceNotFoundException($this->getResourceTypeName(), $id);
        }

        return $this->deserializeResource($data);
    }

    public function findAll(): array
    {
        $it = new Keyspace($this->getRedisClient(), $this->getPrefixedKey("*"));
        $resources = [];
        foreach ($it as $key) {
            $resources[] = $this->deserializeResource($this->getRedisClient()->hgetall($key));
        }
        return $resources;
    }

    public function remove(ResourceInfo $resource)
    {
        $this->getRedisClient()->del([$this->getPrefixedKey($resource->getId())]);
    }

    protected abstract function serializeResource(ResourceInfo $resource): array;

    protected abstract function deserializeResource(array $data);

    public function getRedisClient(): Client
    {
        return $this->redisClient;
    }

    public function getPrefixedKey(string $id): string
    {
        return sprintf(self::KEY_FORMAT, $this->getKeyPrefix(), $id);
    }

    public function getKeyPrefix(): string
    {
        return $this->keyPrefix;
    }

    public abstract function getResourceTypeName(): string;
}
