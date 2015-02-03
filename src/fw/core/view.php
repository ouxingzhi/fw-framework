<?php

namespace Fw\Core;

use Fw\Core\Template;
use Fw\Exception\NotFoundViewFileException;

class View{
	private $template;
	public function __construct($viewpath){
		$this->template = new Template($viewpath);
	}
	public function assign($key,$val){
		$this->template->assign($key,$val);
	}
	public function write($file){
		$this->template->insert($file);
	}
}