<?php

namespace QchainPHP\QchainAPI;

class Qdt
{
	protected $cached = false;
	protected $cacheProvider;
	protected $authorizationManager;
	protected $httpclient;
	protected $apiUrl;
	
	public function __construct(string $apiUrl, Provider\AuthorizationProviderInterface $authorizationManager = null, Provider\CacheProviderInterface $cacheProvider = null)
    {
        $this->apiUrl = $apiUrl;
		$this->httpclient = new \GuzzleHttp\Client([
			'base_uri' => $apiUrl
		]);
		if($authorizationManager) {
			$this->authorizationManager = $authorizationManager;
		}
		if($cacheProvider) {
			$this->cacheProvider = $cacheProvider;
		}
    }
	
	public function setCached($cached = false)
	{
		$this->cached = $cached;
		return $this;
	}
	
	public function qdt($cached = false)
	{
		$this->setCached($cached);
		return $this;
	}
	
	public function __call($name, $arguments) {
		$key = 'qdt_' . $name . md5(print_r($arguments, true));
		if($this->cached) {
			$ret = $this->cacheProvider->get($key);
			if( ! empty($ret)) {
				return $ret;
			}
		}
		
		$data = call_user_func_array(array(&$this, 'getData'), [$name, ($arguments ? $arguments[0] : [])]);
		
		if($this->cached) {
			$this->cacheProvider->set($key, $data);
		}
		return $data;
	}
	
	public function getData($method, $params = [])
	{
		if(isset($params['limit'])) {
			$method .= '/'.$params['limit'];
			unset($params['limit']);
		}
		elseif( ! isset($params['request'])) {
			$method .= '/';
		}
		
		if(isset($params['request'])) {
			$request = $params['request'];
			unset($params['request']);
		}
		else {
			$request = 'GET';
		}
		
		if($request == 'POST') {
			$params = [
				'data' => $params,
				'sign' => $this->authorizationManager->getSign($params)
			];
		}
		
		$response = $this->httpclient->request(
			$request,
			$method,
			[
				($request != 'POST' ? \GuzzleHttp\RequestOptions::QUERY : \GuzzleHttp\RequestOptions::JSON) => $params
			]);
		
		if ($response->getStatusCode() == 200) {
			return json_decode($response->getBody(), true);
		}
		return null;
	}
}
