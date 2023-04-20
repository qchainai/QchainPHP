<?php

namespace QchainPHP\QchainAPI;

use QchainPHP\QchainAPI\Support\ArrayHelper;

class Address extends Client
{
	use Support\Fields;
	
	public $id;
	public $token;
	public $transactionsCount;
	
	private $_fields = ['id', 'token', 'transactionsCount'];
	
	public function __construct(array $address, int $transactionsCount = 0, array $data = [])
	{
		$this->token = $address['token'];
		$this->id = $address['account'];
		$this->transactionsCount = $transactionsCount;
		foreach($data as $key=>$val) {
			$this->{$key} = $val;
		}
		return $this;
	}

	public static function findById($id, $token = null, $offset = 0, $limit = 0)
	{
		$accountsData = self::getClient()->account(['limit' => $id, 'type' => 'balance']);
		return self::find($id, $accountsData, $offset, $limit, $token);
	}

	public static function findByHash($hash, $token = null, $offset = 0, $limit = 0)
	{
		$accountsData = self::getClient()->account(['type' => 'balance']);
		return self::find($hash, $accountsData, $offset, $limit, $token);
	}
	
	protected static function find($accountIdentity, $accountsData, $offset = 0, $limit = 0, $token = null)
	{
		$accounts = [];
		$tokens = [];
		
		if( ! isset($accountsData['balances'])) return [];
		foreach($accountsData['balances'] AS $account) {
			if($account['address'] == $accountIdentity || is_numeric($accountIdentity)) {
				if($account['token'] == $token || $token === null || $token == 'all') {
					$data = [];
					$transactionsCount = 0;
					if($limit) {
						$transactionsData = AddressTransaction::getData($account);
						$transactionsCount = $transactionsData['transactionsCount'];
						if($transactionsCount > $offset) {
							$transactions = AddressTransaction::last($account, $offset, $limit);
						}
						else {
							$transactions = [];
						}
						$data = [
							'recieved' => self::floor($transactionsData['recieved']),
							'send' => self::floor($transactionsData['send']),
							'fees' => self::floor($transactionsData['fees']),
							'transactions' => $transactions,
						];
					}
					$accounts[] = $newAccount = new self($account, $transactionsCount, $data);
					$newAccount->tokens = &$tokens;
				}
				$tokens[] = $account['token'];
			}
		}
		if($token == 'all') {
			return ArrayHelper::index($accounts, 'token');
		}
		elseif( ! empty($accounts)) {
			return $accounts[0];
		}
		return null;
	}
	
	public function getLastTransaction($token = 'QDT')
	{
		$accounts = [];
		$tokens = [];
		
		$accountsData = self::getClient()->account([
			'limit' => $this->account,
			'type' => 'transactions',
			'token' => $token,
			'count' => 1
		]);
			
		if( ! isset($accountsData['data'][0]['transactions'])) return null;
		
		$transactionArray = $accountsData['data'][0]['transactions'][0];
		$transactionArray['from_address'] = '';
		$transactionArray['to_address'] = '';
		
		$transaction = new Transaction(
			$transactionArray
		);
		
		return $transaction;
	}
	
	protected static function floor($sum)
	{
		return preg_replace('/\.\d{7}\K.+/', '', $sum) + 0;
	}
}
