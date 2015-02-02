<?php

namespace Fw\Core;

use Fw\Exception\NotFoundViewFileException;

class View{
	private $__path__;
	public function __construct($path){
		$this->__path__ = $path;
	}
	public function assign($key,$val){
		$this->$key = $val;
	}
	public function isset($key){
		return isset($this->$key);
	}

	public function include($file){
		if(file_exists($this->__path__ . $file)){
			include($this->__path__ . $file);
		}else{
			throws new NotFoundViewFileException($file);
		}
	}
}