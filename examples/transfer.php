<?php

require(__DIR__ . '/../vendor/autoload.php');

define('PRIVATEKEY_FILE', ''); // private file to access node post request here

define('ADDRESSTO', ''); // address to send tokend
define('AMOUNT', 100); // amount of tokens
define('TOKEN', 'qdt'); // token

use QchainPHP\QchainAPI;

QchainPHP\QchainAPI\Client::configure(['node_url' => 'http://212.8.240.85/api/', 'key_file' => PRIVATEKEY_FILE]); // put the address of your node and path to private key here

$nodeResponse = QchainPHP\QchainAPI\Client::getClient()->getData(
	'transfer' . '?' . http_build_query(['token' => TOKEN, 'amount' => AMOUNT]),
	[
		'request' => 'POST',
		'account' => ADDRESSTO
	]
);

if($nodeResponse && isset($nodeResponse['success']) && $nodeResponse['success'] == 'true') {
	echo 'Success';
}