<?php

namespace \Fw\Utils;

class UrlParseFactory{
	public static function getUrlParse($type){

	}
}

interface UrlParseInterface{
	public function getConroller();
	public function getAction();
	public function getParam();
}