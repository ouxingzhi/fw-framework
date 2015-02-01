<?php

use Fw\Core\Controller;

class Index extends Controller{
	public function indexAction($p1,$p2=0){
		var_dump($p1,$p2);
	}
}