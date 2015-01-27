<?php

include_once './src/bootstrap.php';

use Fw\Core\Application;

try{
	new Application();
}catch(Exception $e){
	echo $e;
}
