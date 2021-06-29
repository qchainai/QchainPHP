<?php

namespace QchainPHP\QchainAPI;

class Contract extends Client
{	
	private $contractAlias = '';
	private $index = 0;
	
	public function __construct(string $contractAlias)
	{
		$this->contractAlias = $contractAlias;
		return $this;
	}
	
	public function pay($index = 0, $paymentsAlias = 'pay')
	{
		$payments = [];
		$paymentsList = self::getClient()->qdt()->{$this->contractAlias}(['limit' => $paymentsAlias, 'index' => $index]);
		if( ! empty($paymentsList)) {
			foreach($paymentsList['payments'] AS $payment) {
				$payments[($payment['payment'])] = new Payment($payment);
			}
			$this->index = $paymentsList['index'];
		}
		return $payments;
	}
	
	public function getIndex()
	{
		return $this->index;
	}
	
	public function __call($method, $arguments) {
        
		$data = array_merge(
			['request' => 'POST'],
			$arguments[0]
		);
		
		$nodeResponse = self::getClient()->qdt()->getData(
			$this->contractAlias . '/' . $method,
			$data
		);
		
		if($nodeResponse && isset($nodeResponse['success']) && $nodeResponse['success'] == 'true') {
				
			return true;
		}
		
        return false;
    }
}