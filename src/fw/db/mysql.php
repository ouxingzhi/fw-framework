<?php

namespace Fw\Db;

use Fw\Db\SessionInterface;
use Fw\Core\FwException;
use Fw\Exception\ParamNotExsistException;

class Mysql implements SessionInterface{

	private $host;

	private $port;

	private $user;

	private $passward;

	private $database;



	private $link;

	private $state;

	private static $instance;

	const STATE_NOT_CONNECT = 'notconnect';

	const STATE_CONNECT = 'connect';
	/**
	 * Mysql Session
	 * @options {array}
	 *		|-- host
	 *		|-- user
	 *		|-- passward
	 *		|-- database
	 *		|-- port
	 * 		|-- charset
	 */

	public function __construct($options=array()){
		$this->setOption($options);
	}

	private function setOption($options){
		if(isset($options['host'])){
			$this->host = $options['host'];
		}else{
			throw new ParamNotExsistException('database.host');
		}
		if(isset($options['port'])){
			$this->port = $options['port'];
		}
		if(isset($options['user'])){
			$this->user = $options['user'];
		}else{
			throw new ParamNotExsistException('database.user');
		}
		if(isset($options['passward'])){
			$this->passward = $options['passward'];
		}
		if(isset($options['database'])){
			$this->database = $options['database'];
		}else{
			throw new ParamNotExsistException('database.database');
		}
	}

	public function open(){
		if($this->state === static::STATE_CONNECT) return $this;

		$this->link = mysql_connect($this->host . ($this->port ? ':' . $this->port : ''),$this->user,$this->passward,true);
		if($this->link === false){
			throw new FwException(mysql_error(),3389);
		}
		mysql_select_db($this->database,$this->link);
		$this->state = static::STATE_CONNECT;
		return $this;
	}
	public function close(){
		if($this->state === static::STATE_NOT_CONNECT) return $this;
		mysql_close($this->link);
		$this->state = static::STATE_NOT_CONNECT;
		$this->link = null;
	}

	public function isOpen(){
		return $this->state === static::STATE_CONNECT;
	}

	public function query($query){
		$this->open();
		$resource = mysql_query($query,$this->link);
		if(is_bool($resource)){
			if(!$resource){
				$error = mysql_error();
				if($error){
					throw new FwException($error,3388);
				}
			}
			return $resource;
		}
		$result = false;
		if(is_resource($resource)){
			$result = array();
			while($row = mysql_fetch_array($resource)){
				$result[] = $row;
			}
		}
		return $result;
	}
	pubic function getLastInsertId(){
		return mysql_insert_id();
	}
	public static function getInstance($options){
		if(static::$instance){
			static::$instance->setOpiton($options);
			return static::$instance;
		} 
		return static::$instance = new self($options);
	}

}