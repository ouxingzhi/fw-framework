<?php

use Fw\Core\Controller;

use Models\IndexModel;

class Index extends Controller{
	public function indexAction($p1=0,$p2=0){
		$index = new IndexModel();

		$index->set('TITLE','tidieidiiee');
		$index->set('USER','ouxingzhi');
		var_dump($index->save());
		return 'index';
	}
}