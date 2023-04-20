<?php

namespace QchainPHP\QchainAPI;

class Transfer extends Client
{	
	private $identity;
	private $identityField;
	
	protected $lastError;
	
	public function __construct($identity)
	{
		$this->identity = $identity;
		if(is_numeric($identity)) {
			$this->identityField = 'account';
		}
		else {
			$this->identityField = 'address';
		}
		return $this;
	}
	
	public function send(float $amount, string $token = 'qdt')
	{
		$data = [
			'request' => 'POST',
			'amount' => $amount,
			'token' => $token,
			$this->identityField => $this->identity
		];
		
		$nodeResponse = self::getClient()->request()->getData(
			'transfer',
			$data
		);
		
		if($nodeResponse && isset($nodeResponse['success']) && $nodeResponse['success'] == 'true') {
				
			return true;
		}
		
		if( ! empty($nodeResponse['error'])) {
			$this->lastError = $nodeResponse['error'];
		}
		
        return false;
    }
	
	public function getError()
	{		
        return $this->lastError;
    }
}