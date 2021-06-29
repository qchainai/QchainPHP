<?php

declare(strict_types=1);

namespace QchainPHP\QchainAPI\Provider;

final class AuthorizationProvider implements AuthorizationProviderInterface
{
	private $key;
	
	public function __construct(string $keyPath)
	{
		$this->key = 'file://' . $keyPath;
		return $this;
	}
	
	public function getSign(array $data, string $passphrase = ''): string
	{
		$sign = rtrim(strtr($this->_encrypt(sha1(json_encode($data)), $passphrase), '+/', '-_'), '=');
				
		return $sign;
	}
	
    public function _encrypt($data, $passphrase = '') {
        $maxlength = $this->_getMaxEncryptCharSize($passphrase);
        $output = '';
        while ($data) {
            $input = substr($data, 0, $maxlength);
            $data = substr($data, $maxlength);
            $encrypted = '';
            $result = openssl_private_encrypt($input, $encrypted, $this->_getKey($passphrase));
            if ($result === false) {
                return null;
            }
            $output.=$encrypted;
        }
        return base64_encode($output);
    }
	
	private function _getKey($passphrase) {
        return openssl_pkey_get_private($this->key, $passphrase);
    }

    private function _getCertBits($passphrase) {
        $detail = openssl_pkey_get_details($this->_getKey($passphrase));
        return (isset($detail['bits'])) ? $detail['bits'] : null;
    }

    private function _getCertChars($passphrase) {
        $certLength = $this->_getCertBits($passphrase);
        return $certLength / 8;
    }

    private function _getMaxEncryptCharSize($passphrase) {
        return $this->_getCertChars($passphrase) - 11;
    }

	
}