<?php
namespace Fw\Core;

use Fw\Utils\UrlMapping;
use Fw\Core\Controller;
use Fw\Utils\UrlParseFactory;
use Fw\Core\FwException;
use Fw\Config\Config;
use Fw\Utils\LogCache;

/**
 * Application类
 * 表示一个应用程序，主要用于分发请求，控制流程。
 */
class Application{

	const CONTROLLER_PATH = 'controllers';

	const VIEW_PATH = 'views';
    
    const LAYOUT_PATH = 'layouts';

    const CFGKEY_MAPPING = 'urlmapping';

	private $config;

	private $applicationPath;

	private $urlMapping;

	private $controllersPath;

	private $viewsPath;
    
    private $layoutPath;
    
    private $defaultController = 'index';
    
    private $defaultAction = 'index';

	private $configsPath;
    
    private $debug = false;
    

	public function __construct($config){
		$this->setOption($config);
		$this->config = $config;
		Config::setConfig($this->config);
        $umconfig = Config::get(static::CFGKEY_MAPPING) or array();
		$this->urlMapping = new UrlMapping($umconfig);

		$this->checkOptions();
	}
	public function setOption($config){
		if(isset($config["applicationPath"])){
			$this->applicationPath = realpath($config["applicationPath"]) ;
		}
        if(!$this->applicationPath){
			throw new Exceptoin("not set 'applicationPath'");
		}
        if(isset($config["controllersPath"])){
			$this->controllersPath = realpath($config["controllersPath"]) ;
		}else{
            $this->controllersPath = $this->applicationPath . DIRECTORY_SEPARATOR . self::CONTROLLER_PATH .DIRECTORY_SEPARATOR;
        }
        if(isset($config["viewsPath"])){
			$this->viewsPath = realpath($config["viewsPath"]) ;
		}else{
            $this->viewsPath = $this->applicationPath . DIRECTORY_SEPARATOR . self::VIEW_PATH .DIRECTORY_SEPARATOR;
        }
        if(isset($config["layoutPath"])){
			$this->layoutPath = realpath($config["layoutPath"]) ;
		}else{
            $this->layoutPath = $this->applicationPath . DIRECTORY_SEPARATOR . self::LAYOUT_PATH .DIRECTORY_SEPARATOR;
        }
        
        if(isset($config["defaultController"]) and !empty($config["defaultController"])){
            $this->defaultController = $config["defaultController"];   
        }
        
        if(isset($config["defaultAction"]) and !empty($config["defaultAction"])){
            $this->defaultAction = $config["defaultAction"];   
        }
        
        if(isset($config["debug"]) and !empty($config["debug"])){
            $this->debug = $config["debug"];   
        }
	}
	private function checkOptions(){
		
	}
	public function __destruct(){

	}
	public function run(){

		$pathUrl = $this->getPathUrl();
        LogCache::log('real url' , $pathUrl);
		$url = $this->urlMapping->find($pathUrl);
		if(!$url) $url = $pathUrl;
        LogCache::log('change url' , $url);
		$urltype = isset($this->config['urltype']) ? $this->config['urltype'] : 'path';
		$urlParse = UrlParseFactory::factory($urltype,$url);
		$this->delegate($urlParse);
	}
	private function getPathUrl(){
		return (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '') . (isset($_SERVER['QUERY_STRING']) and !empty($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '');
	}
	private function delegate($urlParse){
		$controller = $urlParse->getController();
		$action = $urlParse->getAction();
        if(empty($controller)) $controller = $this->defaultController;
        
		try{
			$controllerObject = $this->loadController($controller);
            
			if($controllerObject instanceof Controller){
                LogCache::log('controller name' , $controller);
                if(empty($action)){
                    $action = $controllerObject->__getDefaultActionName();
                    if(!$action){
                        $action = $this->defaultAction;
                    }
                }
				$params = $urlParse->getParam();
                $controllerObject->__setApp($this);
				$controllerObject->trigger($action,$controller,$params);
			}else{
				throw new FwException("not extend controller `$controller`!",2);
			}
		}catch(FwException $e){
			
		}

	}
	private function getUrlMapping(){
		
	}
	private function loadController($controller){
		if(file_exists($this->controllersPath . $controller . '.php')){
			include($this->controllersPath . $controller . '.php');
		}else{
			throw new FwException("not found controller `$controller`",1);
		}
		return new $controller($this);
	}
    public function getViewPath(){
        return $this->viewsPath;
    }
    public function getLayoutPath(){
        return $this->layoutPath;   
    }
}
