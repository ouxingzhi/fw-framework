<?php

namespace Fw\Core;

class Request{
    
    public function get($key,$def=null){
        return isset($_GET[$key]) ? $_GET[$key] : $def;
    }
    public function allget(){
        return $_GET;   
    }
    public function post($key,$def=null){
        return isset($_POST[$key]) ? $_POST[$key] : $def;
    }
    public function allpost(){
        return $_POST;   
    }
    public function server($key,$def=null){
        $k = 'HTTP_'  . preg_replace("-","_",strtoupper($key));
        return isset($_SERVER[$k]) ? $_SERVER[$k] : (isset($_SERVER[$key]) ? $_SERVER[$key] : $def);
    }
    public function allserver(){
        return $_SERVER;
    }
    public function cookie($key,$def=null){
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : $def;
    }
    public function allcookie(){
        return $_COOKIE; 
    }
}