<?php

namespace Fw\Utils;

class ArrayUtils{

	public static function getPath(&$array,$path,$def=null){
		$paths = explode('.',$path);
		$obj = &$array;
		foreach($paths as $key=>$p){
            if(!$obj) return $def;
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