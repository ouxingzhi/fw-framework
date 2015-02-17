<?php

include_once '../src/bootstrap.php';

use Fw\Utils\ArrayUtils;

class A{
    
}

$arr = array(
    'show'=>array(
        'tts'=>new A()
    )
);

$r = ArrayUtils::getPath($arr,'show.tts');
ArrayUtils::setPath($arr,'show.tts.oude.dieie','ouxidiieie');

var_dump($arr);