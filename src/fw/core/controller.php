<?php

namespace Fw\Core;

use Fw\Core\View;
use Fw\Exception\NotDefinedMethodException;

class Controller{

	private $app;

	public function __construct($app){
		$this->app = $app;
	}

	const SUFFIX = 'Action';

	private $view = null;
	public function trigger($action,$controller,$params){
		$this->view = new View($this->$app->getViewPath());
		$method = $action . SUFFIX;

		$paths = $params['paths'];
		array_splice($paths,0,2);
		if(method_exists($this,$method)){
			$viewName = call_user_func_array(array($this,$action),$paths);
			if(empty($viewName)){
				$viewName = $controller . '_' .$action;
			}
		}else{
			throws new NotDefinedMethodException($method);
		}
	}

	public function getView(){
		return $this->view;
	}
	protected function assign($key,$val){
		$this->view->assign($key,$val);
	}
}