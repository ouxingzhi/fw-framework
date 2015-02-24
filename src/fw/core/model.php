<?php

namespace Fw\Core;

use Fw\Db\SqlBuild;
use Fw\Core\FwException;
/**
 * Model基类
 */

abstract class Model{

    private $rows = null;

	private $data = array();

	private $last_sql = '';

	public function __construct(){

	}
	/**
	 * get table object
	 * @return Fw\Db\SessionInterface
	 */
	public abstract function getTableObject();
    
    public function load(){
        $table = $this->getTableObject();
        $pk = $table->getPK();
        $pkval = isset($this->data[$pk]) ? $this->data[$pk] : null;
        
        $rows = $table->findOne('*',array(
            $pk=>$pkval
        ));
        if(!empty($rows)){
            $this->rows = $rows;   
        }
        return $this;
    }

	public function save(){
        $table = $this->getTableObject();
        $table->insert($this->data);
        $id = $table->getLastInsertId();
        $pk = $table->getPK();

        $this->data[$pk] = $id;
        $this->load();
		return $this;
	}
	public function update(){
        $pk = $this->getPk();
        $pkval = $this->getPkVal();
        if(empty($pkval)){
            throw new FwException("not setting pk!",21);
        }
        $data = $this->data;
        unset($data[$pk]);
        $table = $this->getTableObject();
        $table->update($data,array(
            $pk=>$pkval
        ));
        $this->load();
		return $this;
	}
	public function delete(){
        $pk = $this->getPk();
        $pkval = $this->getPkVal();
        if(empty($pk) or empty($pkval)){
            throw new FwException("not setting pk!",21);
        }
        $table = $this->getTableObject();
        $table->delete(array(
            $pk=>$pkval
        ));
        $this->data = array();
        $this->rows = array();
		return $this;
	}
    public function getPk(){
        $table = $this->getTableObject();
        $pk = $table->getPK();
        return $pk;
    }
    public function setPkVal($val){
        $pk = $this->getPk();
        $this->set($pk,$val);
        return $this;
    }
    public function getRows(){
        $this->load();
        return $this->rows;
    }
    public function getPkVal(){
        $pk = $this->getPk();
        $data = $this->data;
        $pkval = isset($data[$pk]) ? $data[$pk] : null;
        return $pkval;
    }
	public function set($name,$value){
		$fields = $this->getTableObject()->getFields();
        $name = strtolower($name);
		if($fields && isset($fields[$name])){
			$this->data[$name] = $value;
		}
		return $this;
	}
	public function get($name){
		$fields = $this->getTableObject()->getFields();
        $name = strtolower($name);
		if($fields && isset($fields[$name]) && isset($this->data[$name])){
			return $this->data[$name];
		}
		return null;
	}
}