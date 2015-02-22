<?php

namespace Fw\Core;

class FwException extends \Exception{
	public function __construct($message,$code=0){
		parent::__construct($message,$code);
		$this->writeError();
	}
	protected function writeError(){
		$html = <<<HTML
	<div class="fw-error">
		<div class="fw-error-title">
			<h2>error</h2>
		</div>
		<div class="fw-error-content">
			{$this->message}
		</div>
	</div>
HTML;
		echo $html;
	}
}