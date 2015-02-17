<?php

namespace Fw\Core;

use Fw\Utils\ArrayUtils;
use Fw\Exception\NotFoundViewFileException;
/**
 * Template基类
 * 提供模板内的方法
 */
class Template{

	private $__viewpath__;
    private $__viewdata__;
	public function __construct($path){
        $this->__viewdata__ = array();
		$this->__viewpath__ = $path;
	}
	public function assign($key,$val){
        ArrayUtils::setPath($this->__viewdata__,$key,$val);
	}
	public function get($key){
		return ArrayUtils::getPath($this->__viewdata__,$key);
	}

	public function insert($file){
		if(file_exists($this->__viewpath__ . $file)){
            extract($this->__viewdata__);
			include($this->__viewpath__ . $file);
		}else{
			throw new NotFoundViewFileException($file);
		}
	}
}