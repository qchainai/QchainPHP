<?php

namespace QchainPHP\QchainAPI;

class Chain extends Client
{
	use Support\Fields;
	
    public $id;
    public $hash;
    public $time;
	public $timestamp;
	
	private $_fields = ['id', 'hash', 'time', 'timestamp'];
	
	public function __construct(array $chain)
	{
		$this->hash = $chain['hash'];
		$this->id = $chain['id'];
		$this->time = $chain['time'];
		$this->timestamp = strtotime($chain['time']);
		foreach($chain AS $key=>$val) {
			if( ! property_exists($this, $key)) {
				$this->{$key} = $val;
			}
		}
		return parent::__construct();
	}
	
	public static function list()
	{
		return ChainList::get();
	}
	
	public static function last($name, $page, $pageSize)
	{
		$count = 0;
		$chains = [];
		$chainsData = self::getClient()->qdt()->chains(['limit' => $name, 'page' => $page, 'count' => $pageSize]);
		if( ! $chainsData || ! isset($chainsData['data'])) return [];
		usort($chainsData['data'], function($a, $b) {
			return -($a['id'] <=> $b['id']);
		});
		foreach($chainsData['data'] AS $chain) {
			$chains[] = new self($chain);
		}
		return $chains;
	}
}
