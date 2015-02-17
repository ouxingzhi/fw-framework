<?php

namespace Fw\Utils;

class ArrayUtils{
    private static function setVal(&$obj,&$key,&$val){
        if(is_object($obj)){
            $obj->$key = $val;
        }else if(is_array($obj)){
            $obj[$key] = $val;
        }
    }
    private static function getVal(&$obj,&$key){
        if(is_object($obj)){
            return $obj->$key;
        }else if(is_array($obj)){
            return $obj[$key];
        }
    }
    private static function hasVal(&$obj,&$key){
        if(empty($obj)) return false;
        if(is_object($obj)){
            return isset($obj->$key);
        }else if(is_array($obj)){
            return isset($obj[$key]);   
        }
        return false;
    }
	public static function getPath(&$array,$path){
		$paths = explode('.',$path);
		$obj = &$array;
		foreach($paths as $key=>$p){
            if(!$obj) return null;
			if(is_array($obj)){
                $obj = &$obj[$p];
            }else if(is_object($obj)){
                $obj = &$obj->$p;
            }
		}
		return $obj;
	}
	public static function setPath(&$array,$path,$val){
		$paths = explode('.',$path);
        $max = max(count($paths)-1,0);
        $i = 0;
        $obj = &$array;
		foreach($paths as $key=>$p){
            if(is_array($obj)){
                if($i === $max){
                    $obj[$p] = $val;
                    return;
                }
                if(!isset($obj[$p]) or !$obj[$p]){
                    $obj[$p] = array();
                }
                $obj = &$obj[$p];
            }else if(is_object($obj)){
                if($i === $max){
                    $obj->$p = $val;
                    return;
                }
                if(!isset($obj->$p) or !$obj->$p){
                    $obj->$p = array();
                }
                
                $obj = &$obj->$p;
            }else{
                return;   
            }
            $i++;
		}
	}
}