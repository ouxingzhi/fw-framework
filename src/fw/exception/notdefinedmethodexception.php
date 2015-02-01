<?php

namespace Fw\Exception;

use Fw\Core\FwException;

class NotDefinedMethodException extends FwException{
	public function __construct($method){
		parent::__construct("Not Defined Method '$method' Exception!",1000);
	}
}