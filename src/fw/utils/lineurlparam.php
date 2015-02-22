<?php

namespace Fw\Utils;

use Fw\Utils\UrlParam;

class LineUrlParam extends UrlParam{
    public function __constrct($string){
        parent::__constrct($string);   
    }
    public function groupCut(){
        return '_';
    }
    public function equalCut(){
        return '-';
    }
}