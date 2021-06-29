<?php

namespace QchainPHP\QchainAPI\Provider;


interface AuthorizationProviderInterface
{
    /**
     * Get sign for data array
     *
     * @param array $data
     * @param string $passphrase
     * @return string
     */
    public function getSign(array $data, string $passphrase = ''): string;
}