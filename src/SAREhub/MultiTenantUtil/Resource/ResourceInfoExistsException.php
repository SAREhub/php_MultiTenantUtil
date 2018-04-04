<?php


namespace SAREhub\MultiTenantUtil\Resource;


class ResourceInfoExistsException extends ResourceInfoException
{
    private $resourceType;
    private $resourceId;

    public function __construct(string $resourceType, string $resourceId)
    {
        parent::__construct("Resource of type '$resourceType' and id '$resourceId' exists");
        $this->resourceType = $resourceType;
        $this->resourceId = $resourceId;
    }

    public function getResourceType(): string
    {
        return $this->resourceType;
    }

    public function getResourceId(): string
    {
        return $this->resourceId;
    }
}