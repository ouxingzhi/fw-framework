<?php

namespace Fw\Utils;

class UrlParseFactory{
	public static function factory($type,$url,$ops=array()){
		if($type == 'path'){
			return new PathUrlParse($url,$ops);
		}else if($type == 'query'){
			return new QueryUrlParse($url,$ops);
		}
	}
}

abstract class AbstractUrlParse{

	protected $url;
	protected $controller;
	protected $action;
	protected $paths;
	protected $querys;

	protected $info;

	protected $controllerIndex = 0;

	protected $actionIndex = 1;

	protected $controllerKey = 'controller';

	protected $actionKey = 'action';

	public function __construct($url,$ops=array()){
		$this->url = $url;
		$this->setOption($ops);
		$this->info = $this->parseUrl();
	}

	protected function setOption($ops){
		if(!is_array($ops)) $ops = array();
		if(isset($ops['controllerIndex'])) $this->controllerIndex = $ops['controllerIndex'];
		if(isset($ops['actionIndex'])) $this->actionIndex = $ops['actionIndex'];
		if(isset($ops['controllerKey'])) $this->controllerKey = $ops['controllerKey'];
		if(isset($ops['actionKey'])) $this->actionKey = $ops['actionKey'];
	}

	protected function parseUrl(){
		$info = array();
		$m = parse_url($this->url);
		if(isset($m['path'])){
			$info['path'] = $m['path'];
			$info['paths'] = explode('/',preg_replace("/^\s*\/+|\/+\s*$/im","",$m['path']));
		}else{
			$info['path'] = '';
			$info['paths'] = array();
		}
		if(isset($m['query'])){
			$info['query'] = $m['query'];
			$info['querys'] = array();
			parse_str($m['query'],$info['querys']);
		}else{
			$info['query'] = '';
			$info['querys'] = array();
		}
		return $info;
	}
	public abstract function getController();
	public abstract function getAction();
	public abstract function getParam();
}

class PathUrlParse extends AbstractUrlParse{

	public function __construct($url){
		parent::__construct($url);
	}

	public function getController(){
		if(isset($this->info['paths'][$this->controllerIndex]) and !empty($this->info['paths'][$this->controllerIndex])){
			return $this->info['paths'][$this->controllerIndex];
		}else{
			return null;
		}
	}
	public function getAction(){
		if(isset($this->info['paths'][$this->actionIndex]) and !empty($this->info['paths'][$this->actionIndex])){
			return $this->info['paths'][$this->actionIndex];
		}else{
			return null;
		}
	}
	public function getParam(){
		return array(
			'path'=>$this->info['path'],
			'paths'=>$this->info['paths'],
			'query'=>$this->info['query'],
			'querys'=>$this->info['querys']
		);
	}
}

class QueryUrlParse extends AbstractUrlParse{
	public function __construct($url){
		parent::__construct($url);
	}
	public function getController(){
		if(isset($this->info['querys'][$this->controllerKey])){
			return $this->info['querys'][$this->controllerKey];
		}else{
			return null;
		}
	}
	public function getAction(){
		if(isset($this->info['querys'][$this->actionKey])){
			return $this->info['querys'][$this->actionKey];
		}else{
			return null;
		}
	}
	public function getParam(){
		return array(
			'path'=>$this->info['path'],
			'paths'=>$this->info['paths'],
			'query'=>$this->info['query'],
			'querys'=>$this->info['querys']
		);
	}
}