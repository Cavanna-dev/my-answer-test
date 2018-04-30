<?php

namespace Cache;

use Cache\CacheAdapterAbstract;

class MemoryCacheAdapter extends CacheAdapterAbstract
{
    /**
     * @var array
     */
    private $cache = [];

    /** @inheritdoc */
    public function get(string $key, $default = null)
    {
        if (isset($this->cache[$key])) {
            if ($this->cache[$key]['ttl'] === null || $this->cache[$key]['ttl'] > time()) {
                return $this->cache[$key]['content'];
            }

            // We can't return the value because the Ttl is passed
            // So we wipe this particular $key in the cache
            unset($this->cache[$key]);
        }

        return $default;
    }

    /** @inheritdoc */
    public function set(string $key, $value, int $ttl = null): bool
    {
        $this->cache[$key] = [
            'ttl' => $ttl ? time() + $ttl : null,
            'content' => $value,
        ];

        return true;
    }

    /** @inheritdoc */
    public function delete(string $key): bool
    {
        unset($this->cache[$key]);

        return true;
    }

    /** @inheritdoc */
    public function clear(): bool
    {
        $this->cache = [];

        return true;
    }
}
