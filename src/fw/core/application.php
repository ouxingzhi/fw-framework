<?php
namespace Fw\Core;

use Fw\Utils\UrlMapping;
use Fw\Utils\UrlParseFactory;
use Fw\Exception\NotDefinedMethodException;
use Fw\Exception\NotFoundControllerException;

class Application{

	const CONTROLLER_PATH = 'controllers';

	const MODEL_PATH = 'models';

	const VIEW_PATH = 'views';

	const CONFIG_PATH = 'configs';

	const CFGFILE_APPLICATION = 'application.php';

	const CFGFILE_URL_MAPPING = 'urlmapping.php';

	private $config;

	private $applicationPath;

	private $urlMapping;

	private $controllersPath;

	private $modelsPath;

	private $viewsPath;

	private $configsPath;

	public function __construct($options){
		$this->setOption($options);

		$this->buildPaths();

		$this->config = $this->getApplicationConfig();

		$this->urlMapping = $this->getUrlMapping();

		$this->checkOptions();
	}
	private function buildPaths(){
		$this->controllersPath = $this->applicationPath . DIRECTORY_SEPARATOR . self::CONTROLLER_PATH .DIRECTORY_SEPARATOR;
		$this->modelsPath = $this->applicationPath . DIRECTORY_SEPARATOR . self::MODEL_PATH .DIRECTORY_SEPARATOR;
		$this->viewsPath = $this->applicationPath . DIRECTORY_SEPARATOR . self::VIEW_PATH .DIRECTORY_SEPARATOR;
		$this->configsPath = $this->applicationPath . DIRECTORY_SEPARATOR . self::CONFIG_PATH .DIRECTORY_SEPARATOR;
	}
	public function setOption($options){
		if(isset($options["applicationPath"])){
			$this->applicationPath = realpath($options["applicationPath"]) ;
		}
	}
	private function checkOptions(){
		if(!$this->applicationPath){
			throw new Exceptoin("not set 'applicationPath'");
		}
	}
	public function __destruct(){

	}
	public function run(){

		$pathUrl = $this->getPathUrl();

		$url = $this->urlMapping->find($pathUrl);
		if(!$url) $url = $pathUrl;

		$urltype = isset($this->config['urltype']) ? $this->config['urltype'] : 'path';
		$urlParse = UrlParseFactory::factory($urltype,$url);
		$this->delegate($urlParse);
	}
	private function getPathUrl(){
		return (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '') . (isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : '');
	}
	private function delegate($urlParse){
		$controller = $urlParse->getController();
		$action = $urlParse->getAction() . 'Action';

		try{
			$controllerObject = $this->loadController($controller);
			if(method_exists($controllerObject,$action)){
				$paths = $urlParse->getParam();
				$paths = $paths['paths'];
				array_splice($paths,0,2);
				call_user_func_array(array($controllerObject,$action),$paths);
			}else{
				throw new NotDefinedMethodException($action);
			}
		}catch(NotFoundControllerException $e){
			
		}catch(NotDefinedMethodException $e){

		}

	}
	private function getUrlMapping(){
		$umconfig = $this->loadConfig($this->configsPath . self::CFGFILE_URL_MAPPING);
		return new UrlMapping($umconfig);
	}
	private function loadController($controller){
		if(file_exists($this->controllersPath . $controller . '.php')){
			include($this->controllersPath . $controller . '.php');
		}else{
			throw new NotFoundControllerException($controller);
		}
		return new $controller;
	}
	private function loadConfig($file){
		if(file_exists($file)){
			return include($file);
		}else{
			return array();
		}
	}
	private function getApplicationConfig(){
		return $this->loadConfig($this->configsPath . self::CFGFILE_APPLICATION);
	}
}
