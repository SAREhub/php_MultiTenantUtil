<?php


namespace SAREhub\MultiTenantUtil\Resource;


use Throwable;

class ResourceInfoException extends \Exception
{
    public function __construct(string $message, Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
    }
}