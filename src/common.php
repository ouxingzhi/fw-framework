<?php

	class AutoLoadClass{
		const SUFFIX = '.php';
		public static function autoload($name){
			include_once self::buildFileName($name);
		}
		private static function buildFileName($name){
			return str_replace('\\','/',strtolower($name)) . self::SUFFIX;
		}
	}

	spl_autoload_register('AutoLoadClass::autoload');
