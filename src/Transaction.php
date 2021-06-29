<?php

namespace QchainPHP\QchainAPI;

class Transaction extends Client
{
	use Support\Fields;
	
	public $hash;
	public $blockHash;
	public $blockNumber;
	public $timestamp;
	public $from;
	public $to;
	public $value;
	
	private $_fields = ['hash', 'blockHash', 'timestamp', 'blockNumber', 'from', 'to', 'value'];
	
	public function __construct(array $transaction)
	{
		$this->hash = $transaction['hash'];
		$this->blockHash = $transaction['hash'];
		$this->blockNumber = $transaction['txid'];
		$this->timestamp = strtotime($transaction['time']);
		$this->from = $transaction['from_address'];
		$this->to = $transaction['to_address'];
		$this->value = $transaction['amount'];
		foreach($transaction AS $key=>$val) {
			if( ! property_exists($this, $key)) {
				$this->{$key} = $val;
			}
		}
		return $this;
	}
	
	public static function last($offset, $limit)
	{
		$transactions = [];
		$page = floor($offset / $limit) + 1;
		$count = $limit;
		$transactionsData = self::getClient()->transactions(['count' => $count, 'page' => $page]);
		if( ! isset($transactionsData['transactions'])) return [];
		usort($transactionsData['transactions'], function($a, $b) {
			return -($a['txid'] <=> $b['txid']);
		});
		foreach($transactionsData['transactions'] AS $transaction) {
			$transactions[] = new self($transaction);
		}
		return $transactions;
	}

	
	public static function findByHash($hash)
	{
		return new self(self::getClient()->transaction(['txhash' => $hash]));
	}
}
