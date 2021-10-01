<?php

declare(strict_types=1);

namespace App\ValueObject\Service;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Port
{
    private int $host;
    private int $container;

    public function __construct(string $host, string $container)
    {
        if (!preg_match('#^\d+$#', $host)) {
            throw new \LogicException(sprintf('Port %s has to be a number', $host));
        }

        if (!preg_match('#^\d+$#', $container)) {
            throw new \LogicException(sprintf('Port %s has to be a number', $container));
        }

        $host = (int) $host;
        $container = (int) $container;

        if ($host < 0 || $host > 65535) {
            throw new \LogicException(sprintf('Port %s out of range', $host));
        }

        if ($container < 0 || $container > 65535) {
            throw new \LogicException(sprintf('Port %s out of range', $container));
        }

        $this->host = $host;
        $this->container = $container;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getContainer(): string
    {
        return $this->container;
    }
}
