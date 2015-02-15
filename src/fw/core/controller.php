<?php

namespace Fw\Core;

use Fw\Core\View;
use Fw\Exception\NotDefinedMethodException;
/**
 * Controller基类
 */
class Controller{

	private $app;

	public function __construct($app){
		$this->app = $app;
	}

	const SUFFIX = 'Action';

	private $view = null;
	public function trigger($action,$controller,$params){
		$this->view = new View($this->app->getViewPath());
		$method = $action . self::SUFFIX;

		$paths = $params['paths'];
		array_splice($paths,0,2);
		if(method_exists($this,$method)){
			$viewName = call_user_func_array(array($this,$method),$paths);
			if(empty($viewName)){
				$viewName = $controller . '_' .$action;
			}
			$this->view->write($viewName);
		}else{
			throw new NotDefinedMethodException($method);
		}
	}

	public function getView(){
		return $this->view;
	}
	protected function assign($key,$val){
		$this->view->assign($key,$val);
	}
}