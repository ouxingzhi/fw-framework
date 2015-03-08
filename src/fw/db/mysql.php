<?php

namespace Fw\Db;

use Fw\Db\SessionInterface;
use Fw\Core\FwException;
use Fw\Utils\LogCache;

class Mysql implements SessionInterface{

	private $host;

	private $port;

	private $user;

	private $passward;

	private $database;

    private $charset = 'utf8';

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
			throw new FwException("param not exists `database.host`");
		}
		if(isset($options['port'])){
			$this->port = $options['port'];
		}
		if(isset($options['user'])){
			$this->user = $options['user'];
		}else{
			throw new FwException("param not exists `database.user`");
		}
		if(isset($options['password'])){
			$this->password = $options['password'];
		}
		if(isset($options['database'])){
			$this->database = $options['database'];
		}else{
			throw new FwException("param not exists `database.database`");
		}
        if(isset($options['charset'])){
			$this->charset = $options['charset'];
		}
	}

	public function open(){
		if($this->state === static::STATE_CONNECT) return $this;

		$this->link = mysql_connect($this->host . ($this->port ? ':' . $this->port : ''),$this->user,$this->password,true);
		if($this->link === false){
			throw new FwException(mysql_error(),3389);
		}
		mysql_select_db($this->database,$this->link);
        mysql_set_charset($this->charset,$this->link);
        mysql_query('SET NAMES ' . $this->charset,$this->link);
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
					throw new FwException($error.'<br /><br />SQL : '.$query,3388);
				}
			}
            LogCache::log('SQL' , $query); 
			return $resource;
		}else{
            LogCache::log('SQL' , $query);  
        }
		$result = false;
		if(is_resource($resource)){
			$result = array();
			while($row = mysql_fetch_assoc($resource)){
				$result[] = $row;
			}
		}
		return $result;
	}
    public function countQuery($query){
        $this->open();
		$resource = mysql_query($query,$this->link);
        if(is_bool($resource)){
			if(!$resource){
				$error = mysql_error();
				if($error){
					throw new FwException($error.'<br /><br />SQL : '.$query,3388);
				}
			}
            LogCache::log('SQL' , $query); 
			return 1;
		}else{
            LogCache::log('SQL', $query);  
        }
        if($resource){
            return mysql_num_rows($resource);
        }
        return 0;
    }
	public function getLastInsertId(){
		return mysql_insert_id($this->link);
	}
	public static function getInstance($options){
		if(static::$instance){
			static::$instance->setOpiton($options);
			return static::$instance;
		} 
		return static::$instance = new self($options);
	}

}