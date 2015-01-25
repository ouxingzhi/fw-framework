<?php
	/*
	 * define INCLUDE_PATH constant
	 */

	define('INCLUDE_PATH',__DIR__);

	/*
	 * set the `include_path` config
	 */
	set_include_path(INCLUDE_PATH);

	include_once INCLUDE_PATH . DIRECTORY_SEPARATOR . 'common.php';