<?php

	class LoadClass{
		const SUFFIX = '.php';
		
		private static $includePaths = array();

		public static function autoload($name){
			include_once self::buildFileName($name);
		}
		private static function buildFileName($name){
			return str_replace('\\','/',strtolower($name)) . self::SUFFIX;
		}
		public static function addIncludePath($path){
			$realpath = realpath($path);
			if(array_search($realpath,self::$includepaths) !== false){
				if(file_exists($realpath)){
					set_include_path($realpath);
				}else{
					throw new Execption("\"$realpath\" is not exist");
				}
			}
		}
		public static function existFileInIncludePath($file){
			
		}
	}

	spl_autoload_register('LoadClass::autoload');

	function import($class){

	}
