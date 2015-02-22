<?php

namespace Fw\Core;

use Fw\Utils\ArrayUtils;
use Fw\Core\FwException;
/**
 * Template基类
 * 提供模板内的方法
 */
class Template{

	private $__viewpath__;
    private $__viewdata__;
    private $__section__ = array();
	public function __construct($path){
        $this->__viewdata__ = array();
		$this->__viewpath__ = $path;
	}
	public function assign($key,$val=null){
        ArrayUtils::setPath($this->__viewdata__,$key,$val);
	}
	public function get($key,$def=''){
		$result = ArrayUtils::getPath($this->__viewdata__,$key);
        if($result) return $result;
        return $def;
	}
    public function g($key,$def=''){
        return $this->get($key,$def);   
    }
    
    public function v($obj,$path,$def=''){
        if(!$obj) return $def;
        return ArrayUtils::getPath($obj,$path,$def);
    }
    
    public function getViewData(){
        return $this->__viewdata__;
    }

	public function insert($file,$params=array()){
		if(file_exists($this->__viewpath__ . $file)){
            extract($this->__viewdata__);
            extract($params);
			include($this->__viewpath__ . $file);
		}else{
			throw new FwException("not found view file `$file`",5);
		}
	}
    public function startSection(){
        ob_start();  
    }
    public function endSection($name){
        $value = ob_get_clean();
        if(!isset($this->__section__[$name]) or !is_array($this->__section__[$name])){
            $this->__section__[$name] = array();
        }
        $this->__section__[$name][] = $value;
    }
    public function echoSection($name){
        if(isset($this->__section__[$name]) and is_array($this->__section__[$name])){
            echo implode('',$this->__section__[$name]);
        }
    }
}