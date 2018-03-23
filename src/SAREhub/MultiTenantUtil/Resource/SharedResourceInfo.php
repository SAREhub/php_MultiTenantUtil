<?php


namespace SAREhub\MultiTenantUtil\Resource;


class SharedResourceInfo extends ResourceInfo
{
    /**
     * @var int
     */
    private $currentAccounts = 0;

    /**
     * @var int
     */
    private $maxAccounts = 1;

    /**
     * @var array
     */
    private $attributes = [];

    public function __construct(string $id, array $attributes)
    {
        parent::__construct($id);
        $this->attributes = $attributes;
    }

    public function getCurrentAccounts(): int
    {
        return $this->currentAccounts;
    }

    public function setCurrentAccounts(int $currentAccounts)
    {
        $this->currentAccounts = $currentAccounts;
    }

    public function getMaxAccounts(): int
    {
        return $this->maxAccounts;
    }

    public function setMaxAccounts(int $maxAccounts)
    {
        $this->maxAccounts = $maxAccounts;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "currentAccounts" => $this->getCurrentAccounts(),
            "maxAccounts" => $this->getMaxAccounts(),
            "attributes" => $this->getAttributes()
        ];
    }
}