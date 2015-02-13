<?php

namespace Fw\Config;

use Fw\Utils\ArrayUtils;

class Config{
	private static $config = array();
	public static function get($path){
		return ArrayUtils::getPath(static::$config,$path);
	}

	public static function setConfig($config){
		static::$config = $config;
	}
}