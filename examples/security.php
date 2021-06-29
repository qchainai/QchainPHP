<?php

require(__DIR__ . '/../vendor/autoload.php');

define('PRIVATEKEY_FILE', ''); // private file to access node post request here
define('PRIVATEKEY_FILE_PASSPHRASE', ''); // example - get passphrase from security place

define('CONTRACT', 'payments/cryptolocal'); // contract alias

define('AMOUNT', 200); // amount of tokens
define('TOKEN', 'qdt'); // token

use QchainPHP\QchainAPI;

$authorizationProvider = new QchainPHP\QchainAPI\Provider\AuthorizationProvider(PRIVATEKEY_FILE, PRIVATEKEY_FILE_PASSPHRASE);

QchainPHP\QchainAPI\Client::configure(['node_url' => 'http://212.8.240.85/api/', 'authorization_provider' => $authorizationProvider]);

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