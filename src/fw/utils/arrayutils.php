<?php

namespace Fw\Utils;

class ArrayUtils{
	public static function getPath($array,$path){
		$result = null;
		$paths = explode('.',$path);
		$max = max(count($paths)-1,0);
		$i = 0;
		$arr = &$array;

		foreach($paths as $key=>$p){
			if($i != $max && !is_array($arr) or !isset($arr[$p])) return $result;
			if($i === $max) {
				$result = $arr[$p];
			}

			$arr = $arr[$p];
			$i++;
		}
		return $result;
	}
	public static function setPath(&$array,$path,$val){
		$paths = explode('.',$path);
		$max = max(count($paths)-1,0);
		$i = 0;
		$arr = &$array;

		foreach($paths as $key=>$p){
			if(!isset($arr[$p]) or !$arr[$p]) $arr[$p] = array();
			if(!is_array($arr[$p])) return null;
			if($i === $max){
				$arr[$p] = $val;
			}
			$arr = &$arr[$p];
			$i++;
		}
	}
}