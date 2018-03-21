<?php


namespace SAREhub\MultiTenantUtil;


class DatabaseHost implements \JsonSerializable
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    /**
     * @var int
     */
    private $currentAccounts = 0;

    /**
     * @var int
     */
    private $maxAccounts = 1;

    /**
     *
     * @param string $id
     * @param string $host
     * @param int $port
     */
    public function __construct(string $id, string $host, int $port)
    {
        $this->id = $id;
        $this->host = $host;
        $this->port = $port;
    }


    public function getId(): string
    {
        return $this->id;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function getCurrentAccounts(): int
    {
        return $this->currentAccounts;
    }

    public function getMaxAccounts(): int
    {
        return $this->maxAccounts;
    }

    public function setCurrentAccounts(int $currentAccounts)
    {
        $this->currentAccounts = $currentAccounts;
    }

    public function setMaxAccounts(int $maxAccounts)
    {
        $this->maxAccounts = $maxAccounts;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            "id" => $this->getId(),
            "host" => $this->getHost(),
            "port" => $this->getPort(),
            "currentAccounts" => $this->getCurrentAccounts(),
            "maxAccounts" => $this->getMaxAccounts()
        ];
    }
}