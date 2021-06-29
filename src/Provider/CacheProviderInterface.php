<?php

namespace QchainPHP\QchainAPI\Provider;


interface CacheProviderInterface
{
    /**
     * Save data to cache
     *
     * @param string $key
     * @return bool
     */
    public function set(string $key, array $data): bool;

    /**
     * Get data from cache
     *
     * @param string $key
     * @return array
     */
    public function get(string $key): array;
}