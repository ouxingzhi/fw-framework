<?php

    define('FW',true);

	define('FW_LIBRARY_PATH',__DIR__);


	include_once FW_LIBRARY_PATH . DIRECTORY_SEPARATOR . 'loadclass.php';

    
	LoadClass::addIncludePath(FW_LIBRARY_PATH);


