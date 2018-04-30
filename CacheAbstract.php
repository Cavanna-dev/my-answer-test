<?php

namespace Cache;

use Cache\CacheAdapaterInterface;

abstract class CacheAdapterAbstract implements CacheAdapaterInterface
{
    /** @inheritdoc */
	public function has(string $key): bool
    {
		return (bool) $this->get($key);
	}

	/** @inheritdoc */
	public function getMultiple(array $keys, $default = null): array
    {
		$data = [];

		foreach ($keys as $key) {
			$data[$key] = $this->get($key, $default);
		}

		return $data;
	}

	/** @inheritdoc */
	public function setMultiple(array $values, int $ttl = null): bool
    {
		$return = [];

		foreach($values as $key => $value){
			$return[] = $this->set($key, $value, $ttl);
		}

		return $this->checkReturn($return);
	}

	/** @inheritdoc */
	public function deleteMultiple(array $keys): bool
    {
		$return = [];

		foreach ($keys as $key) {
			$return[] = $this->delete($key);
		}

		return $this->checkReturn($return);
	}

	/**
	 * @param bool[] $booleans
	 *
	 * @return bool
	 */
	protected function checkReturn(array $booleans): bool
    {
		foreach ($booleans as $bool {
			if (! (bool) $bool) {
				return false;
			}
		}

		return true;
	}
}
