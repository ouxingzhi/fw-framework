<?php
	/*
	 * define FW_LIBRARY_PATH constant
	 */

	define('FW_LIBRARY_PATH',__DIR__);

	/*
	 * 
	 */
	include_once FW_LIBRARY_PATH . DIRECTORY_SEPARATOR . 'common.php';


	LoadClass::addIncludePath(FW_LIBRARY_PATH);


