<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\Cache\Adapter\AdapterInterface;

/**
 * @author Laurent Bassin <laurent@bassin.info>
 */
class Cache
{
    private AdapterInterface $adapter;

    public function __construct(AdapterInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    public function has(string $key): bool
    {
        $key = $this->normalizeKey($key);

        $item = $this->adapter->getItem($key);

        return $item->isHit();
    }

    public function get(string $key)
    {
        $key = $this->normalizeKey($key);

        $item = $this->adapter->getItem($key);
        if (!$item->isHit()) {
            return null;
        }

        return $item->get();
    }

    public function set(string $key, $data): void
    {
        $key = $this->normalizeKey($key);

        $item = $this->adapter->getItem($key);
        $item->set($data);
        $item->expiresAt(null);

        $this->adapter->save($item);
    }

    private function normalizeKey(string $key): string
    {
        return str_replace('/', '.', $key);
    }
}
