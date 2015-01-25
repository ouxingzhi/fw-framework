<?php




	

include_once './src/bootstrap.php';

try{
new Core\Application();
}catch(Exception $e){
	echo $e;
}
