<?php

namespace QchainPHP\QchainAPI;

class AddressTransaction extends Client
{
	use Support\Fields;
	
	public $hash;
	public $timestamp;
	public $from;
	public $to;
	public $value;
	public $from_account;
	public $to_account;
	public $token;
	
	private $_fields = ['hash', 'timestamp', 'from', 'to', 'value', 'from_account', 'to_account', 'token'];
	
	public function __construct(array $transaction, array $address)
	{
		$this->hash = $transaction['hash'];
		$this->timestamp = strtotime($transaction['time']);
		if($transaction['amount'] >= 0) {
			$this->from = $transaction['address'];
			$this->to = $address['address'];
			$this->from_account = $transaction['account'];
			$this->to_account = $address['account'];
			$this->value = $transaction['amount'];
		}
		else {
			$this->from = $address['address'];
			$this->to = $transaction['address'];
			$this->from_account = $address['account'];
			$this->to_account = $transaction['account'];
			$this->value = -$transaction['amount'];
		}
		$this->from_address = $this->from;
		$this->to_address = $this->to;
		$this->amount = abs($transaction['amount']);
		$this->token = $address['token'];
		
		foreach($transaction AS $key=>$val) {
			if( ! property_exists($this, $key)) {
				$this->{$key} = $val;
			}
		}
		return $this;
	}
	
	public static function last($address, $offset, $limit)
	{
		$count = 0;
		$transactions = [];
		
		$transactionsData = self::getClient()->qdt()->account(['limit' => $address['account'], 'type' => 'transactions', 'token' => $address['token'], 'count'=> $offset + $limit]);
		
		if( ! isset($transactionsData['data'][0]['transactions'])) return [];
		foreach($transactionsData['data'][0]['transactions'] AS $transaction) {
			if($count >= $offset) {
				$transactions[] = new self($transaction, $address);
			}
			++$count;
		}
		return $transactions;
	}
	
	public static function getData($address)
	{
		$data = [
			'transactionsCount' => 0,
			'recieved' => 0,
			'send' => 0,
			'fees' => 0
		];
		
		$latest = self::getClient()->qdt(false)->account(['limit' => $address['account'], 'type' => 'transactions', 'token' => $address['token'], 'count'=> 1]);
		
		if(isset($latest['data'][0]['transactions']) && ! empty($latest['data'][0]['transactions'])) {
			$latestTxid = $latest['data'][0]['transactions'][0]['txid'];
			$transactionsData = self::getClient()->qdt(true)->account(['limit' => $address['account'], 'type' => 'transactions', 'token' => $address['token'], 'count'=> '9999999', 'last' => $latestTxid]);
		}
		
		if( ! isset($transactionsData['data'][0]['transactions'])) return $data;
		
		foreach($transactionsData['data'][0]['transactions'] AS $transaction) {
			if($transaction['amount'] >= 0) {
				$data['recieved'] += $transaction['amount'];
			}
			else {
				$data['send'] -= $transaction['amount'];
			}
			$data['transactionsCount']++;
		}
		
		return $data;
	}
}
