<?php

class A{
	public function __construct(){
		echo 'A::__constructor';
	}
	private static $instance = null;
	public static function  getInstance(){
		if(self::$instance) return self::$instance;
		return self::$instance = new self();
	}
}

A::getInstance();

