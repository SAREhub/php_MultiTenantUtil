<?php


namespace SAREhub\MultiTenantUtil\Resource;


class AccountSharedResourceInfo extends ResourceInfo
{
    /**
     * @var string
     */
    private $sharedResourceId;

    public function __construct(string $id, string $sharedResourceId)
    {
        parent::__construct($id);
        $this->sharedResourceId = $sharedResourceId;
    }

    public function getSharedResourceId(): string
    {
        return $this->sharedResourceId;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "sharedResourceId" => $this->sharedResourceId
        ];
    }
}
