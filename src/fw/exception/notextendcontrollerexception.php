<?php

namespace Fw\Exception;

use Fw\Core\FwException;

class NotExtendControllerException extends FwException{
	public function __construct($controller){
		parent::__construct("Not Extend Controller '$controller' Exception!",1005);
	}
}