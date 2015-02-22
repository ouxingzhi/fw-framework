<?php
    /**
     * 类自动加载器
     */
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
			if(array_search($realpath,self::$includePaths) === false){
				if(file_exists($realpath)){
					set_include_path(get_include_path() . PATH_SEPARATOR . $realpath);
					self::$includePaths[] = $realpath;
				}else{
					throw new Execption("\"$realpath\" is not exist");
				}
			}
		}
	}
    //注册当类未定义时的处理方法
	spl_autoload_register('LoadClass::autoload');

