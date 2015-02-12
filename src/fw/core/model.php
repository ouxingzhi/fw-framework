<?php

namespace Fw\Core;

use Fw\Db\SqlBuild;

abstract class Model{

	const TYPE_INT = 'int';

	const TYPE_STRING = 'string';

	const TYPE_DATE = 'date';

	const TYPE_DATETIME = 'datetime';

	const __TABLE = '$TABLE';

	const __PK = '$PK';

	const __FIELD = '$FIELD';

	private $data = array();

	private $last_sql = '';

	public function __construct(){

	}
	/**
	 * get database sessdion object
	 * @return Fw\Db\SessionInterface
	 */
	public abstract function getDbSession();
	/**
	 * get table schema
	 * @return {array}
	 */
	public abstract function getSchema();


	public function save(){
		$sql = new SqlBuild();
		$sql->insert($this->getTable())
			->values($this->buildSqlValuesSetString());
		$this->last_sql = $sql->build();
		$db = $this->getDbSession();

		$db->query($this->last_sql);
		$id = $db->getLastInsertId();
		$pk = $this->getPK();
		$this->data[$pk ? $pk : 'id'] = $id;
		return $this;
	}
	public function update($where=array()){
		$sql = new SqlBuild();
		$sql->update($this->getTable())
			->values($this->buildSqlValuesSetString())
			->where(static::buildSqlConds($where));
		$this->last_sql = $sql->build();

		
		$db = $this->getDbSession();

		$db->query($this->last_sql);
		$id = $db->getLastInsertId();
		$pk = $this->getPK();
		$this->data[$pk ? $pk : 'id'] = $id;
		return $this;
	}
	public function delete($where=array()){
		$sql = new SqlBuild();
		$sql->delete($this->getTable())
			->where(static::buildSqlConds($where));
		$this->last_sql = $sql->build();
		
		$db = $this->getDbSession();

		$db->query($this->last_sql);
		return $this;
	}
	public function find($fields=array('*'),$where=array(),$order=""){
		$sql = new SqlBuild();
		$sql->select($fields)
			->from($this->getTable())
			->where(static::buildSqlConds($where));
		if($order){
			$sql->order($order);
		}
		$this->last_sql = $sql->build();
		
		$db = $this->getDbSession();

		$db->query($this->last_sql);
	}
	public function set($name,$value){
		$fields = $this->getFields();
		if($fields && isset($fields[$name])){
			$this->data[$name] = $value;
		}
		return $this;
	}
	public function get($name){
		$fields = $this->getFields();
		if($fields && isset($fields[$name]) && isset($this->data[$name])){
			return $this->data[$name];
		}
		return null;
	}
	public function getFields(){
		$schema = $this->getSchema();
		if(isset($schema[static::__FIELD])){
			return $schema[static::__FIELD];
		}else{
			return null;
		}
	}
	public function getTable(){
		$schema = $this->getSchema();
		if(isset($schema[static::__TABLE])){
			return $schema[static::__TABLE];
		}else{
			return null;
		}
	}
	public function getPK(){
		$schema = $this->getSchema();
		if(isset($schema[static::__PK])){
			return $schema[static::__PK];
		}else{
			return null;
		}
	}

	public function buildSqlValuesSetString(){
		$values = array();
		$schema = $this->getSchema();
		$fields = $schema[static::__FIELD];
		foreach($fields as $field=>$define){
			$def = explode('|',$define);
			$type = strtolower($def[0]);
			$defval = isset($def[1]) ? strtolower($def[1]) : null;
			if(isset($this->data[$field])){
				$values[] = static::buildSqlValue($field,$this->data[$field],$type);
			}else if($defval){
				$values[] = static::buildSqlDefaultValue($field,$type,$defval);
			}
		}
		return implode(',',$values);
	}

	public static function buildSqlConds($where){
		$builds = array();
		foreach($where as $key=>$val){
			if(is_numeric($key)){
				if(is_string($val) and preg_match("/^or|and$/i",$val)){
					$builds[] = strtoupper($val);
				}else if(is_array($val)){
					$builds[] = '(' . static::buildSqlConds($val) . ')';
				}
			}else{
				$builds[] = "`$key`=\"$val\"";
			}
		}
		return implode(' ',$builds); 
	}
	private static function buildSqlValue($field,$value,$type){

		switch($type){
			
			case static::TYPE_STRING:
				$str = "\"" . $value . "\"";
				break;
			case static::TYPE_DATE:
			case static::TYPE_DATETIME:
				if(gettype($value) === 'integer'){
					$str = "\"" . date($type === static::TYPE_DATE ? 'Y-m-d' : 'Y-m-d h:i:s',$value) ."\"";
				}else{
					$str = "\"" . $value . "\"";
				}
				break;
			case static::TYPE_INT:
			default:
				$str = $value;
				break;
		}
		return '`' . $field . '`' . '=' . $str;
	}
	private static function buildSqlDefaultValue($field,$type,$defval){
		$reg = '/^([a-z]\w+)\(([^\(\)]*)\)$/im';
		$fnname = null;
		$fnparam = null;
		if(!preg_match($reg,$defval,$matchs)){
			$fnname = $matchs[1];
			$fnparam = $matchs[2];
		}
		switch($type){
			
			case static::TYPE_STRING:
				$str = "\"" . $value . "\"";
				break;
			case static::TYPE_DATE:
				$str = $fnname ? static::datefilter($fnname,$fnparam) : "\"$defval\"";
				break;
			case static::TYPE_DATETIME:
				$str = $fnname ? static::datetimefilter($fnname,$fnparam) : "\"$defval\"";
				break;
			case static::TYPE_INT:
			default:
				$str = $value;
				break;
		}
		return '`' . $field . '`' . '=' . $str;
	}
	private static function datefilter($fnname,$fnparam){
		switch($fnname){
			case 'now':
				return "\"" . date("Y-m-d") . "\"";
		}
		return "\"\"";
	}
	private static function datetimefilter($fnname,$fnparam){
		switch($fnname){
			case 'now':
				return "\"" . date("Y-m-d h:i:s") . "\"";
		}
		return "\"\"";
	}
}