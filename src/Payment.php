<?php

namespace QchainPHP\QchainAPI;

class Payment extends Client
{
	use Support\Fields;

	private $_fields = [];
	
	public function __construct(array $transaction)
	{
		foreach($transaction AS $key=>$val) {
			if( ! property_exists($this, $key)) {
				$this->{$key} = $val;
			}
		}
		return $this;
	}
	
	public static function get($contractAlias, $index = 0)
	{
		$payments = [];
		$paymentsList = self::getClient()->qdt()->contract(['limit' => $contractAlias . '/pay', 'index' => $index]);
		foreach($paymentsList['payments'] AS $payment) {
			$payments[($payment['payment'])] = new self($payment);
		}
		return $payments;
	}
}
