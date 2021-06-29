<?php

require(__DIR__ . '/../vendor/autoload.php');

use QchainPHP\QchainAPI;

class MyCacheProvider implements QchainPHP\QchainAPI\Provider\CacheProviderInterface{ // only for example
	
	protected $cache = [];
	
	public function set(string $key, array $data): bool
	{
		$this->cache[$key] = serialize($data);
		return true;
	}

    public function get(string $key): array
	{
		if(isset($this->cache[$key])) {
			return unserialize($this->cache[$key]);
		}
		return [];
	}
}

$cacheProvider = new MyCacheProvider;

QchainPHP\QchainAPI\Client::configure(['node_url' => 'http://212.8.240.85/api/', 'cache_provider' => $cacheProvider]);

QchainPHP\QchainAPI\Client::getClient()->setCached(true); // enable caching

$address = QchainPHP\QchainAPI\Address::findByHash('Rh4nmFgwNrS0MeeESi5onHhxPsT1NfgK');

var_dump($address);

$address = QchainPHP\QchainAPI\Address::findByHash('Rh4nmFgwNrS0MeeESi5onHhxPsT1NfgK');

var_dump($address);

QchainPHP\QchainAPI\Client::getClient()->setCached(true); // disable caching