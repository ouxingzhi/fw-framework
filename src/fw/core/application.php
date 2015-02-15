<?php
namespace Fw\Core;


use Fw\Utils\UrlMapping;
use Fw\Core\Controller;
use Fw\Utils\UrlParseFactory;
use Fw\Exception\NotDefinedMethodException;
use Fw\Exception\NotFoundControllerException;
use Fw\Exception\NotExtendControllerException;
use Fw\Config\Config;

/**
 * Application类
 * 表示一个应用程序，主要用于分发请求，控制流程。
 */
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
		Config::setConfig($this->config);
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
		$action = $urlParse->getAction();

		try{
			$controllerObject = $this->loadController($controller);
			if($controllerObject instanceof Controller){
				$params = $urlParse->getParam();
				$controllerObject->trigger($action,$controller,$params);
			}else{
				throw new NotExtendControllerException($controller);
			}
		}catch(NotFoundControllerException $e){
			
		}catch(NotDefinedMethodException $e){

		}

	}
	private function getUrlMapping(){
		$umconfig = $this->loadConfig(self::CFGFILE_URL_MAPPING);
		return new UrlMapping($umconfig);
	}
	private function loadController($controller){
		if(file_exists($this->controllersPath . $controller . '.php')){
			include($this->controllersPath . $controller . '.php');
		}else{
			throw new NotFoundControllerException($controller);
		}
		return new $controller($this);
	}
	private function loadConfig($file){

		$fullfile = $this->configsPath . $file;
		if(file_exists($fullfile)){
			return include($fullfile);
		}else{
			return array();
		}
	}
	private function getApplicationConfig(){
		return $this->loadConfig(self::CFGFILE_APPLICATION);
	}
	public function getViewPath(){
		return $this->viewsPath;
	}
}
