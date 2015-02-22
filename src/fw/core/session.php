<?php

namespace Fw\Core;

use Fw\Config\Config;

class Session{
    const STATE_NOT_INIT = 0;
    const STATE_INIT = 1;
    private $state = Session::STATE_NOT_INIT;
    public function __construct(){
        $this->init();
    }
    private function init(){
        if($this->state === static::STATE_INIT) return;
        $session_name = Config::get('session_name','__life__');
        session_name($session_name);
        session_start();
        $this->state = static::STATE_INIT;
    }
    public function set($name,$val){
        $this->init();
        $_SESSION[$name] = $val;
    }
    public function get($name,$def=null){
        $this->init();
        return isset($_SESSION[$name]) ? $_SESSION[$name] : $def;
    }
    public function remove($name){
        $this->init();
        $result = $this->get($name);
        unset($_SESSION[$name]);
        return $result;
    }
}