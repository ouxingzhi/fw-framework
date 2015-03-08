<?php

namespace Fw\Utils;

class LogCache{
    private static $logs = array();
    public static function log($key='',$val='',$overide=false){
        if(!$val){
            $val = $key;
            $key = '';
        }
        if(!isset(static::$logs[$key]) or $overide){
            static::$logs[$key] = array();
        }
        static::$logs[$key][] = $val;
    }
    public static function getCache(){
        $logs = static::$logs;
        static::$logs = array();
        return $logs;
    }
}