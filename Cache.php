<?php

namespace Cache;

use Cache\CacheAdapaterInterface;
use Psr\SimpleCache\CacheInterface;
use Cache\InvalidArgumentException;

class Cache implements CacheInterface
{
    /**
     * Object to interact with files.
     *
     * @var CacheAdapaterInterface
     */
    private $adapter;

    /**
     * The TTL value by default.
     *
     * @var null|int|\DateInterval
     */
    private $defaultTtl;

    /**
     * @param CacheAdapaterInterface $adapter
     * @param null|int|\DateInterval $defaultTtl
     */
    public function __construct(
        CacheAdapaterInterface $adapter,
        $defaultTtl
    ) {
        $this->adapter = $adapter;
        $this->$defaultTtl = $defaultTtl;
    }

    /**
     * Fetches a value from the cache.
     *
     * @param string $key     The unique key of this item in the cache.
     * @param mixed  $default Default value to return if the key does not exist.
     *
     * @return mixed The value of the item from the cache, or $default in case of cache miss.
     */
    public function get($key, $default = null)
    {
        $this->checkIfKeyIsLegal($key);

        if (!$this->has($key)) {
            return $default;
        }

        return $this->adapter->get($key);
    }

    /**
     * Persists data in the cache, uniquely referenced by a key with an optional expiration TTL time.
     *
     * @param string                 $key   The key of the item to store.
     * @param mixed                  $value The value of the item to store, must be serializable.
     * @param null|int|\DateInterval $ttl   Optional. The TTL value of this item. If no value is sent and
     *                                      the driver supports TTL then the library may set a default value
     *                                      for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     */
    public function set($key, $value, $ttl = null): bool
    {
        $this->checkIfKeyIsLegal($key);

        return $this->adapter->setMultiple([$key => $value], $ttl);
    }

    /**
     * Deletes an item from the cache by its unique key.
     *
     * @param string $key The unique cache key of the item to delete.
     *
     * @return bool True if the item was successfully removed. False if there was an error.
     */
    public function delete($key): bool
    {
        $this->checkIfKeyIsLegal($key);

        return $this->adapter->delete($key);
    }

    /**
     * Wipes clean the entire cache's keys.
     *
     * @return bool True on success and false on failure.
     */
    public function clear(): bool
    {
        return $this->adapter->clear();
    }

    /**
     * Obtains multiple cache items by their unique keys.
     *
     * @param iterable $keys    A list of keys that can obtained in a single operation.
     * @param mixed    $default Default value to return for keys that do not exist.
     *
     * @return iterable A list of key => value pairs. Cache keys that do not exist or are stale will have $default as value.
     */
    public function getMultiple($keys, $default = null)
    {
        foreach ($values as $key => $value) {
            $this->checkIfKeyIsLegal($key);
        }

        return $this->adapter->getMultiple($keys, $default);
    }

    /**
     * Persists a set of key => value pairs in the cache, with an optional TTL.
     *
     * @param iterable               $values A list of key => value pairs for a multiple-set operation.
     * @param null|int|\DateInterval $ttl    Optional. The TTL value of this item. If no value is sent and
     *                                       the driver supports TTL then the library may set a default value
     *                                       for it or let the driver take care of that.
     *
     * @return bool True on success and false on failure.
     */
    public function setMultiple($values, $ttl = null): bool
    {
        $values = $this->getData($values);

        foreach ($values as $key => $value) {
            $this->checkIfKeyIsLegal($key);
        }

        return $this->adapter->setMultiple($values, $this->normalizeTtl($ttl));
    }

    /**
     * Deletes multiple cache items in a single operation.
     *
     * @param iterable $keys A list of string-based keys to be deleted.
     *
     * @return bool True if the items were successfully removed. False if there was an error.
     */
    public function deleteMultiple($keys): bool
    {
        $keys = $this->getData($keys);

        foreach ($values as $key => $value) {
            $this->checkIfKeyIsLegal($key);
        }

        return $this->adapter->deleteMultiple($keys);
    }

    /**
     * Determines whether an item is present in the cache.
     *
     * @param string $key The cache item key.
     *
     * @return bool True on success and false on failure.
     */
    public function has($key): bool
    {
        $this->checkIfKeyIsLegal($key);

        return $this->adapter->has($key);
    }

    /**
     * Determines if a value is a string.
     *
     * @param mixed $valueToCheck The value to check.
     *
     * @throws InvalidArgumentException
     *   MUST be thrown if the $key string is not a legal value.
     *
     * @return bool True on success.
     */
    private function checkIfKeyIsLegal($keyToCheck): bool
    {
        if (is_string($keyToCheck) && !empty($keyToCheck)) {
            return true;
        }

        throw new InvalidArgumentException('Invalid data');
    }

    /**
     * @param mixed $data
     *
     * @throws InvalidArgumentException
     *
     * @return array
     */
    private function getData($data): array
    {
        if ($data instanceof \Traversable) {
            return iterator_to_array($data);
        } elseif (is_array($data)) {
            return $data;
        }

        throw new InvalidArgumentException('Invalid data');
    }

    /**
     * Normalizes the Ttl format.
     *
     * @param mixed $ttl The Ttl value to normalize.
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    private function normalizeTtl($ttl)
    {
        if (null === $ttl) {
            return $this->defaultTtl;
        }

        if ($ttl instanceof \DateInterval) {
            $ttl = (int) \DateTime::createFromFormat('U', 0)->add($ttl)->format('U');
        }

        if (\is_int($ttl)) {
            return 0 < $ttl ? $ttl : false;
        }

        throw new InvalidArgumentException(
            sprintf(
                'Expiration date must be an integer, a DateInterval or null, "%s" given',
            is_object($ttl) ? get_class($ttl) : gettype($ttl)
            )
        );
    }
}
