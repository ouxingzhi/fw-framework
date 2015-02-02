<?php

namespace Fw\Exception;

use Fw\FwExcetion;

class NotFoundViewFileException extends FwExcetion{
	public function __construct($file){
		parent::__construct("Not Found View File '$file' Exception!",1006);
	}
}