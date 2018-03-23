<?php


namespace SAREhub\MultiTenantUtil\Resource;


class ResourceInfo implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var array
     */
    private $fields;

    public function __construct(string $id, $fields = [])
    {
        $this->id = $id;
        $this->fields = $fields;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getField(string $name)
    {
        return $this->fields[$name] ?? null;
    }

    public function hasField(string $name): bool
    {
        return isset($this->fields[$name]);
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "fields" => $this->fields
        ];
    }

}
