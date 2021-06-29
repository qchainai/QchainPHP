<?php

namespace QchainPHP\QchainAPI;

use QchainPHP\QchainAPI\Provider\CacheProvider;
use QchainPHP\QchainAPI\Provider\AuthorizationProvider;

class Client
{
	static protected $client = null;
	
	static protected $config = [
		'node_url' => '',
		'key_file' => ''
	];
	
	public static function configure($config)
	{
		if(empty($config['authorization_provider']) && empty(self::$config['authorization_provider'])) {
			$config['authorization_provider'] = new AuthorizationProvider(empty($config['key_file']) ? self::$config['key_file'] : $config['key_file']);
		}
		if(empty($config['cache_provider']) && empty(self::$config['cache_provider'])) {
			$config['cache_provider'] = new CacheProvider;
		}
		self::$config = array_merge(self::$config, $config);
	}
	
	public static function getClient($config = null)
	{
		if( ! empty($config)) {
			self::configure($config);
		}
		if(empty(self::$client) || ! empty($config)) {
			self::$client = new Qdt(
				self::$config['node_url'],
				self::$config['authorization_provider'],
				self::$config['cache_provider']);
		}
		return self::$client;
	}
}
