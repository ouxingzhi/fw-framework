<?php

namespace Fw\Core;

use Fw\Utils\ArrayUtils;
use Fw\Exception\NotFoundViewFileException;

class Layout{
    const DEFAULT_LAYOUT = 'layout.php';
    private $__layoutPath__;
    private $__template__;
    private $__viewname__;
    public function __construct($layoutPath,$template){
        $this->__layoutPath__ = $layoutPath;
        $this->__template__ = $template;
    }
    public function getViewName(){
        return $this->__viewname__;
    }
    public function setViewName($viewname){
        $this->__viewname__ = $viewname;
    }
    public function output($file){
        if(file_exists($this->__layoutPath__ . $file)){
            //extract($this->__viewdata__);
			include($this->__layoutPath__ . $file);
		}else{
			throw new NotFoundViewFileException($file);
		}
    }
    public function assign($key,$val){
        $this->__template__->assign($key,$val);
	}
	public function get($key){
		return $this->__template__->get($key);
	}

	public function insert($file){
		$this->__template__->insert($file);
	}
}