<?php

use Fw\Core\Controller;

class Index extends Controller{
	public function indexAction($p1=0,$p2=0){

		$this->assign();
		return 'index'
	}
}