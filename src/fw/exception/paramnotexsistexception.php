<?php

namespace Fw\Exception;

use Fw\Core\FwException;

class ParamNotExsistException extends FwException{
	public function __construct($paramName){
		parent::__construct("Param '$paramName' Not Exsist Exception!",1008);
	}
}