<?php
namespace Fw\Core;

use Fw\Config\Config;

class Application{

	const CONTROLLER_PATH = 'controllers';

	const MODEL_PATH = 'models';

	const VIEW_PATH = 'views';

	const CONFIG_PATH = 'configs';

	private $applicationPath;

	public function __construct($options){
		$this->setOption($options);

		$this->checkOptions();
	}
	public function setOption($options){
		if(isset($options["applicationPath"])){
			$this->applicationPath = $options["applicationPath"];
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
		
	}
}
