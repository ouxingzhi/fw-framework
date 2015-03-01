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
    //action方法后缀
	const SUFFIX = 'Action';
    //view实例
	private $view = null;
    //触发方法
	public function trigger($action,$controller,$params){
		$this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $paths = $params['paths'];
        
        $isSimpleMode = $this->__isSimpleMode();
        if($isSimpleMode){
            $method = $this->__handleRequestName();
            $paths = array_splice($paths,1);
        }else{
            $method = $action . self::SUFFIX;
            $paths = array_splice($paths,2);
        }
		
		
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
                    //在simple模式，直接使用controller名作为view名
                    if($isSimpleMode){
                        $viewName = $controller . '.php';
                    }else{
                        $viewName = $controller . '/' .$action . '.php';
                    }
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
    //钩子方法，通过覆写此方法来设置自定义view
    protected function __getView($viewPath,$layoutPath){
       return new View($viewPath,$layoutPath);
    }
    //设置app
    public function __setApp($app){
        $this->app = $app;
    }   
    //钩子方法，子类可以通过覆写此方法来改写当前默认layout
    public function __getLayoutName(){
           
    }
    //获得view对象
	public function getView(){
		return $this->view;
	}
    //向模板添加数据
	protected function assign($key,$val){
		if(isset($this->view) and $this->view){
            $this->view->assign($key,$val);
        }
	}
    //获得已设置过的数据
    protected function get($key,$def=null){
        return $this->view->get($key,$def);   
    }
    //获得request
    protected function getRequest(){
        return $this->request;   
    }
    //获得response
    protected function getResponse(){
        return $this->response;   
    }
    //获得session
    protected function getSession(){
        return $this->session;   
    }
    //钩子方法，在action方法或是handleRequest方法之前执行，如果返回true则不执行后续action
    protected function __before($controller,$action){
        
    }
    //钩子方法，在action方法或是handleRequest方法执行完后执行
    protected function __after($controller,$action){
        
    }
    //钩子方法，controller子类可以通过重写此方法来设置为simple模式
    protected function __isSimpleMode(){
        return false;   
    }
    //钩子方法，获得simple模式下，默认处理方法名称
    protected function __handleRequestName(){
        return 'handleRequest';   
    }
    //钩子方法，正常模式下，获得默认action方法
    public function __getDefaultActionName(){
        
    }
    /**
     * simple模式下的处理函数
     */
    protected function handleRequest(){
        throw new FwException("not override method `handleRequest`!",3);
    }
    
}