<?php

namespace Fw\Core;

class Template{

	const FILE_SUFFIX = '.php';

	private $__viewpath__;
	public function __construct($path){
		$this->__viewpath__ = $path;
	}
	public function assign($key,$val){
		$this->$key = $val;
	}
	public function has($key){
		return isset($this->$key);
	}

	public function insert($file){
		if(file_exists($this->__viewpath__ . $file . self::FILE_SUFFIX)){
			include($this->__viewpath__ . $file . self::FILE_SUFFIX);
		}else{
			throw new NotFoundViewFileException($file . self::FILE_SUFFIX);
		}
	}
}