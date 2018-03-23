<?php


namespace SAREhub\MultiTenantUtil\Resource;


abstract class ResourceInfo implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public abstract function toArray(): array;

}
