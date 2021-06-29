<?php

namespace QchainPHP\QchainAPI\Support;

trait Fields
{	
    public function fields()
    {
        return $this->_fields;
    }
	
	public function __set($name, $value) 
    {
		$this->_fields[] = $name;
        $this->{$name} = $value;
    }
}
