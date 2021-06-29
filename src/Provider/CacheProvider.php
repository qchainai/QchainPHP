<?php

namespace QchainPHP\QchainAPI\Provider;


class CacheProvider implements CacheProviderInterface
{
    /**
     * Save data to cache
     *
     * @param string $key
     * @return array
     */
    public function set(string $key, array $data): bool
	{
		return false;
	}

    /**
     * Get data from cache
     *
     * @param string $key
     * @return array
     */
    public function get(string $key): array
	{
		return [];
	}
}