<?php

namespace QchainPHP\QchainAPI\Support;

class ArrayHelper
{
	public static function index($array, $index)
	{
		$arr = [];
		foreach($array AS $element) {
			if(is_array($element)) {
				if(isset($element[$index])) {
					$arr[($element[$index])] = $element;
				}
				else {
					$arr[] = $element;
				}
			}
			if(is_object($element)) {
				if(property_exists($element, $index)) {
					$arr[$element->{$index}] = $element;
				}
				else {
					$arr[] = $element;
				}
			}
		}
		return $arr;
	}
}
