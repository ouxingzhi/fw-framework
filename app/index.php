<?php

include_once '../src/bootstrap.php';

use Fw\Core\Application;

LoadClass::addIncludePath(__DIR__);

$app = new Application(array(
	'applicationPath'=>__DIR__,
    'database'=>include('configs/database.php')
));

$app->run();