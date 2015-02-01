<?php

namespace Fw\Exception;

use Fw\Core\FwException;

class NotFoundControllerException extends FwException{
	public function __construct($controller){
		parent::__construct("Not Found Controller '$controller' Exception!",1003);
	}
}