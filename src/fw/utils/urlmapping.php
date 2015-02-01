<?php

namespace Fw\Utils;

class UrlMapping{

	private $nativeFields = "/\{(\\d+|path|query)\}/";

	/**
	 *
	 */
	private $urlMapping;

	public function __construct($urlMapping){
		$this->urlMapping = $urlMapping;
	}

	public function find($url){
		if(!is_array($this->urlMapping)) return null;
		foreach($this->urlMapping as $key=>$val){
			if($url = $this->match($url,$key,$val)) return $url;
		}
		return null;
	}
	/**
	 * matches url
	 * @param $url {string}
	 * @param $match {regexp}
	 * @param $dest {string}
	 *		|- supports `{1}` or `{path}` or `{query}
	 */
	function match($url,$match,$dest){
		$rmatch = '/.*' . $match . '.*/im';

		if(preg_match($rmatch,$url)){
			if(preg_match($this->nativeFields,$dest)){
				$urlmodule = parse_url($url);
				if(isset($urlmodule['query'])){
					$urlmodule['query'] = '?' . $urlmodule['query'];
				}else{
					$urlmodule['query'] = '';
				} 
				$dest = preg_replace_callback($this->nativeFields,function($ms) use($urlmodule,$rmatch,$url){
					if(isset($urlmodule[$ms[1]])){
						return $urlmodule[$ms[1]];
					}else if(is_numeric($ms[1])){
						return preg_replace($rmatch,"$".$ms[1],$url);
					}else{
						return $ms[0];
					}
				},$dest);
			}
			return $dest;
		}else{
			return false;
		}
	}
}