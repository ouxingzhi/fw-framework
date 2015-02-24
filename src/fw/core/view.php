<?php

namespace Fw\Core;

use Fw\Core\Template;
use Fw\Core\Layout;


class View{
	private $template;
    private $layout;
	public function __construct($viewpath,$layoutpath){
		$this->template = $this->__getTemplate($viewpath);
        $this->layout = $this->__getLayout($layoutpath,$this->template);
	}
	public function assign($key,$val){
		$this->template->assign($key,$val);
	}
    public function get($key,$def=null){
        return $this->template->get($key,$def);   
    }
	public function write($file,$layout=null){
        if(empty($layout)){
            $layout = Layout::DEFAULT_LAYOUT;   
        }
        $this->layout->setViewName($file);
		$this->layout->output($layout);
	}
    public function __getTemplate($viewpath){
        return new Template($viewpath); 
    }
    public function __getLayout($layoutpath,$template){
        return new Layout($layoutpath,$template);
    }
    public function getTemplate(){
        return $this->template;
    }
    public function getLayout(){
        return $this->layout;   
    }
}