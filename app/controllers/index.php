<?php

use Fw\Core\Controller;

use Models\IndexModel;
use Fw\Utils\ArrayUtils;

class Index extends Controller{
	public function indexAction($p1=0,$p2=0){
	
		return array(
            'layout'=>'layout.php'
        );
	}
}