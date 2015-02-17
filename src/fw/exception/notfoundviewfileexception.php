<?php

namespace Fw\Exception;

use Fw\Core\FwException;

class NotFoundViewFileException extends FwException{
	public function __construct($file){
		parent::__construct("Not Found View File '$file' Exception!",1006);
	}
}