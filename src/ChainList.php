<?php

namespace QchainPHP\QchainAPI;

class ChainList extends Client
{
    public $name;
    public $count;
    public $title;
	
	private $_fields = ['name', 'count', 'title'];
	private $prefix;
	
	public function __construct($name, $count)
	{
		$this->name = $name;
		$this->count = $count;
		$this->title = preg_replace('/[^A-Za-z]/', ' ', ucfirst($name));
		list($this->prefix) = explode(' ', $this->title);
		return parent::__construct();
	}
	
	public function getPrefix()
	{
		return $this->prefix;
	}
	
	public static function get()
	{
		$chains = [];
		$chainList = self::getClient()->qdt()->chains();
		foreach($chainList['chains'] AS $chain) {
			foreach($chain AS $name=>$count)
				$chains[] = new self($name, $count);
		}
		return $chains;
	}
}
