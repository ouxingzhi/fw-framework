<?php

namespace Fw\Utils;

abstract class UrlParam{
    private $string;
    private $keyvals;
    public function __construct($string){
        $this->string = $string;
        $this->parse();
    }
    abstract public function groupCut();
    abstract public function equalCut();
    
    public function get($key,$def=null){
        return isset($this->keyvals[$key]) ? $this->keyvals[$key] : $def;
    }
    public function set($key,$val){
        $this->keyvals[$key] = $val;
    }
    public function remove($key){
        unset($this->keyvals[$key]);
    }
    public function parse(){
        $groupcut = $this->groupCut();
        $equalcut = $this->equalCut();
        $this->keyvals = array();
        $kv = !empty($this->string) ? explode($groupcut,urldecode($this->string)) : array();
        foreach($kv as $key=>$val){
            $m = explode($equalcut,$val);
            $this->keyvals[$m[0]] = isset($m[1]) ? $m[1] : null;
        }
    }
    public function reset($string){
        $this->__construct($string);   
    }
    public function build($add=array(),$remove=array()){
        $groupcut = $this->groupCut();
        $equalcut = $this->equalCut();
        
        $builds = array();
        $keyvals = $this->keyvals;
        //删除值
        foreach($remove as $key=>$val){
            unset($keyvals[$val]);  
        }
        //加入值
        foreach($add as $key=>$val){
            $keyvals[$key] = $val;   
        }
        
        foreach($keyvals as $key=>$val){
            $builds[] = urlencode($key) . $equalcut . urlencode($val);   
        }
        return implode($groupcut,$builds);
    }
}