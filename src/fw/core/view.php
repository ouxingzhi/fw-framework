<?php

namespace Fw\Core;

use Fw\Core\Template;
use Fw\Core\Layout;


class View{
	private $template;
    private $layout;
	public function __construct($viewpath,$layoutpath){
		$this->template = new Template($viewpath);
        $this->layout = new Layout($layoutpath,$this->template);
	}
	public function assign($key,$val){
		$this->template->assign($key,$val);
	}
    public function get($key){
        return $this->template->get($key);   
    }
	public function write($file,$layout){
        if(empty($layout)){
            $layout = Layout::DEFAULT_LAYOUT;   
        }
        $this->layout->setViewName($file);
		$this->layout->output($layout);
	}
}