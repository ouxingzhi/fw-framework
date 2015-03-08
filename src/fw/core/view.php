<?php

namespace Fw\Core;

use Fw\Core\Template;
use Fw\Core\Layout;
use Fw\Utils\LogCache;


class View{
	private $template;
    private $layout;
	public function __construct($viewpath,$layoutpath){
		$this->template = $this->__getTemplate($viewpath);
        $this->layout = $this->__getLayout($layoutpath,$this->template);
	}
	public function assign($key,$val){
		$this->template->assign($key,$val);
	}
    public function get($key,$def=null){
        return $this->template->get($key,$def);   
    }
	public function write($file,$layout=null){
        if(empty($layout)){
            $layout = Layout::DEFAULT_LAYOUT;   
        }
        $this->layout->setViewName($file);
		$this->layout->output($layout);
	}
    public function __getTemplate($viewpath){
        return new Template($viewpath); 
    }
    public function __getLayout($layoutpath,$template){
        return new Layout($layoutpath,$template);
    }
    public function getTemplate(){
        return $this->template;
    }
    public function getLayout(){
        return $this->layout;   
    }
    public function writeLog(){
        $logs = LogCache::getCache();
        $html = array();
        $html[] = '<table width="100%" border="1" cellpadding="0" cellspacing="0">';
        $html[] = '<tr><th colspan="2"><h2>debuger</h2></th></tr>';
        foreach($logs as $key=>$list){
            $html[] = '<tr>';
            $html[] = '<th align="left">'.(empty($key) ? 'default' : $key ).'</th>';
            $html[] = '<td>';
            foreach($list as $i=>$msg){
                $html[] = '<p>' . $msg . '</p>';
            }
            $html[] = '</td>';
            $html[] = '</tr>';
        }
        $html[] = '</table>';
        echo implode('',$html);
    }
}