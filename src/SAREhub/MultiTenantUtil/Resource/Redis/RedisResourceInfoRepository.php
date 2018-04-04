<?php


namespace SAREhub\MultiTenantUtil\Resource\Redis;


use Predis\Client;
use Predis\Collection\Iterator\Keyspace;
use Predis\Transaction\MultiExec;
use SAREhub\MultiTenantUtil\Resource\ResourceInfo;
use SAREhub\MultiTenantUtil\Resource\ResourceInfoExistsException;
use SAREhub\MultiTenantUtil\Resource\ResourceInfoNotFoundException;
use SAREhub\MultiTenantUtil\Resource\ResourceInfoRepository;

class RedisResourceInfoRepository implements ResourceInfoRepository
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

    /**
     * @var string
     */
    private $resourceTypeName;

    public function __construct(Client $redisClient, string $keyPrefix, string $resourceTypeName)
    {
        $this->redisClient = $redisClient;
        $this->keyPrefix = $keyPrefix;
        $this->resourceTypeName = $resourceTypeName;
    }

    public function insert(ResourceInfo $resource)
    {
        $key = $this->getPrefixedKey($resource->getId());
        $data = $this->serializeResource($resource);

        $options = ["cas" => true, "watch" => $key, "retry" => 0];

        $this->redisClient->transaction($options, function (MultiExec $tx) use ($key, $resource, $data) {
            if ($tx->exists($key)) {
                throw new ResourceInfoExistsException($this->getResourceTypeName(), $resource->getId());
            }
            $tx->multi();
            $tx->hmset($key, $data);
        });
    }

    public function replace(ResourceInfo $resource)
    {
        $key = $this->getPrefixedKey($resource->getId());
        $data = $this->serializeResource($resource);
        $this->redisClient->hmset($key, $data);
    }

    public function exists(string $id): bool
    {
        return (bool)$this->redisClient->exists($this->getPrefixedKey($id));
    }

    /**
     * @param string $id
     * @return ResourceInfo
     * @throws ResourceInfoNotFoundException
     */
    public function find(string $id): ResourceInfo
    {
        $data = $this->getRedisClient()->hgetall($this->getPrefixedKey($id));
        if (empty($data)) {
            throw new ResourceInfoNotFoundException($this->getResourceTypeName(), $id);
        }

        return $this->deserializeResource($id, $data);
    }

    public function findAll(): array
    {
        $it = new Keyspace($this->getRedisClient(), $this->getPrefixedKey("*"));
        $resources = [];
        foreach ($it as $key) {
            $id = $this->getIdFromKey($key);
            $fields = $this->getRedisClient()->hgetall($key);
            $resources[] = $this->deserializeResource($id, $fields);
        }
        return $resources;
    }

    public function delete(string $id)
    {
        $this->getRedisClient()->del([$this->getPrefixedKey($id)]);
    }

    function serializeResource(ResourceInfo $resource): array
    {
        return $resource->getFields();
    }

    protected function deserializeResource(string $id, array $fields)
    {
        return new ResourceInfo($id, $fields);
    }

    public function getRedisClient(): Client
    {
        return $this->redisClient;
    }

    private function getIdFromKey(string $key): string
    {
        return explode(":", $key)[1];
    }

    public function getPrefixedKey(string $id): string
    {
        return sprintf(self::KEY_FORMAT, $this->getKeyPrefix(), $id);
    }

    public function getKeyPrefix(): string
    {
        return $this->keyPrefix;
    }

    public function getResourceTypeName(): string
    {
        return $this->resourceTypeName;
    }
}
