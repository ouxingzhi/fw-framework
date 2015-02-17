<?php

namespace Fw\Config;

use Fw\Utils\ArrayUtils;

class Config{
	private static $config = array();
	public static function get($path,$def=null){
		$result = ArrayUtils::getPath(static::$config,$path);
        if($result){
            return $result;
        }else{
            return $def;   
        }
	}
    public static function set($path,$val){
        ArrayUtils::setPath($path,$val);   
    }

	public static function setConfig($config){
		static::$config = $config;
	}
}