<?php

require(__DIR__ . '/../vendor/autoload.php');

use QchainPHP\QchainAPI;

QchainPHP\QchainAPI\Client::configure(['node_url' => 'http://212.8.240.85/api/']); // put the address of your node here

$address = QchainPHP\QchainAPI\Address::findById(13);

var_dump($address);

$address = QchainPHP\QchainAPI\Address::findByHash('Rh4nmFgwNrS0MeeESi5onHhxPsT1NfgK');

var_dump($address);

$transaction = QchainPHP\QchainAPI\Transaction::findByHash('d9ecdaf0c1a53691f15deca73e881a63a290ef89962b614be7b2def698cf7f73');

var_dump($transaction);