<?php

namespace Fw\Core;

use Fw\Core\View;
use Fw\Config\Config;
use Fw\Core\FwException;
use Fw\Core\Request;
use Fw\Core\Response;
use Fw\Core\Session;

/**
 * Controller基类
 */
class Controller{
    
    private $request;
    
    private $response;
    
    private $session;

	private $app;

	public function __construct($app){
		$this->__setApp($app);
	}
    
	const SUFFIX = 'Action';

	private $view = null;
	public function trigger($action,$controller,$params){
		$this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        
		$method = $action . self::SUFFIX;

		$paths = $params['paths'];
		array_splice($paths,0,2);
		if(method_exists($this,$method)){
            $this->view = $this->__getView($this->app->getViewPath(),$this->app->getLayoutPath());
            if($this->__before($controller,$action)){
                return;   
            }
			$pageinfo = call_user_func_array(array($this,$method),$paths);
            
            if(!empty($pageinfo)){
                
                if(is_bool($pageinfo)){
                    $pageinfo = null;   
                }
                $viewName = null;
                $layout = null;
                if(is_array($pageinfo)){
                    if(isset($pageinfo['layout']) and $pageinfo['layout']){
                        $layout = $pageinfo['layout'];
                    }
                    if(isset($pageinfo['view']) and $pageinfo['view']){
                        $viewName = $pageinfo['view'];
                    }
                }else{
                    $viewName = $pageinfo;
                }
                if(empty($viewName)){
                    $viewName = $controller . '/' .$action . '.php';
                }
                if(empty($layout)){
                    $layout = $this->__getLayoutName();
                    if(empty($layout)){
                        $layout = Config::get('layout');  
                    }
                }
                $this->view->write($viewName,$layout);
            }
            $this->__after($controller,$action);
		}else{
			throw new FwException("not defined method `$method`!",3);
		}
	}
    protected function __getView($viewPath,$layoutPath){
       return new View($viewPath,$layoutPath);
    }
    public function __setApp($app){
        $this->app = $app;
    }   
    public function __getLayoutName(){
           
    }
	public function getView(){
		return $this->view;
	}
	protected function assign($key,$val){
		if(isset($this->view) and $this->view){
            $this->view->assign($key,$val);
        }
	}
    protected function get($key,$def=null){
        return $this->view->get($key,$def);   
    }
    protected function getRequest(){
        return $this->request;   
    }
    protected function getResponse(){
        return $this->response;   
    }
    protected function getSession(){
        return $this->session;   
    }
    protected function __before($controller,$action){
        
    }
    protected function __after($controller,$action){
        
    }
    public function getDefaultAction(){
        
    }
}