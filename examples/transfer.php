<?php

require(__DIR__ . '/../vendor/autoload.php');

define('PRIVATEKEY_FILE', ''); // private file to access node post request here

define('ADDRESSTO', '1'); // address or node id;
// If you use a string address that does not exist on the network, the node will return an error "unknown account"
define('AMOUNT', 100); // amount of tokens
define('TOKEN', 'qdt'); // token

use QchainPHP\QchainAPI;

QchainPHP\QchainAPI\Client::configure(['node_url' => 'http://212.8.240.85/api/', 'key_file' => PRIVATEKEY_FILE]); // put the address of your node and path to private key here


$transfer = new QchainPHP\QchainAPI\Transfer(ADDRESSTO);

$ret = $transfer->send(AMOUNT, TOKEN);

if($ret) {
	echo 'Success';
}
else {
	echo $transfer->getError();
}