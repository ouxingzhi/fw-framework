<?php

namespace Fw\Core;

class Response{
    public function header($str,$replace=true,$http_response_code=null){
        header($str,$replace,$http_response_code);     
    }
    public function location($url){
        $this->header('Location: ' . $url);   
    }
    public function cookie($name , $value , $expire = 0, $path=null, $domain=null,$secure=false,$httponly=false){
        setcookie($name , $value , $expire, $path, $domain,$secure,$httponly);   
    }
    public function echoJson($obj,$def=array()){
        if(!$obj){
            $obj = $def;   
        }
        echo json_encode($obj);   
    }
    
}