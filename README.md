# Qchain API
A PHP API for interacting with the Qchain Node

## Requirements

The following versions of PHP are supported by this version.

* PHP 7.3

## Example Usage

```php
use QchainPHP\QchainAPI;

Client::configure(['node_url' => 'http://212.8.240.85/api/']); // put the address of your node here
$address = QchainPHP\QchainAPI\Address::findById(13);
$address = QchainPHP\QchainAPI\Address::findByHash('Rh4nmFgwNrS0MeeESi5onHhxPsT1NfgK');
$transaction = QchainPHP\QchainAPI\Transaction::findByHash('d9ecdaf0c1a53691f15deca73e881a63a290ef89962b614be7b2def698cf7f73');

var_dump($address);



```

## Generate key files

```bash
> openssl genrsa -out private.key 2048
> openssl rsa -in private.key -pubout -out key.pub
```

## QPayments integration steps

1. Make a request to receive the list of changes in payments, passing the index value of the last successful request (or 0 for the first request). If the node is unavailable or returned an error, terminate the execution.
2. Based on the results of the request, change the status of the payment in the database. Here you can also process new paid or canceled payments, but better do it at step #4.
3. If no errors occurred, remember the new index value from the response for the next request.
4. Process new paid or canceled payments based on data from the database (change the payment status to "processed" if the processing finished without errors, or process it later if an error occurred).