<?php

require(__DIR__ . '/../vendor/autoload.php');

define('PRIVATEKEY_FILE', ''); // private file to access node post request here

define('CONTRACT', 'payments/cryptolocal'); // contract alias

define('AMOUNT', 200); // amount of tokens
define('TOKEN', 'qdt'); // token

use QchainPHP\QchainAPI;

QchainPHP\QchainAPI\Client::configure(['node_url' => 'http://212.8.240.85/api/', 'key_file' => PRIVATEKEY_FILE]);

// issue an invoice

$contract = new QchainPHP\QchainAPI\Contract(CONTRACT);
$ret = $contract->new([
		'payment' => 212, // local payment id
        'addressfrom' => 'pMkjK9fA7afl8XRSqNlkLAgB8bgm9YSX', // or accountfrom - for node id
        'addressto' => 'a6JUo4y93McUDHASb7KVp50A4NdVEJFa', // or accountto - for node id
        'token' => TOKEN,
        'amount' => AMOUNT
]);

if($ret) {
	echo 'Success';
}

// check payments

$index = 0;

$newPayments = $contract->pay($index);
var_dump($newPayments);

$index = $contract->getIndex(); // we save it for future use