<?php

namespace Fw\Db;

class SqlBuild{
	private $builds;
	public function __construct(){
		$this->clean();
	}
	public function clean(){
		$this->builds = array();
		return $this;
	}
	public function select($fields="*"){
		$this->clean();
		if(is_array($fields)){
			$strval = array();
			foreach($fields as $key=>$val){
				if($val !== '*'){
					$strval[] = "`$val`";
				}else{
					$strval[] = $val;
				}
			}
			$fields = implode(',',$strval);
		}
		$this->builds[] = "SELECT " . $fields;
		return $this;
	}
	public function from($from){
		$this->builds[] = "FROM `$from`";
		return $this;
	}
	public function where($conds){
		$this->builds[] = "WHERE $conds";
		return $this;
	}
	public function group($group){
		$this->builds[] = "GROUP BY $group";
		return $this;
	}
	public function order($order){
		$this->builds[] = "ORDER BY $order";
		return $this;
	}
	public function limit($limit){
		$this->builds[] = "ORDER BY $limit";
		return $this;
	}
	public function delete($table){
		$this->clean();
		$this->builds[] = "DELETE FROM `$table`";
		return $this;
	}
	public function insert($table){
		$this->clean();
		$this->builds[] = "INSERT INTO `$table`";
		return $this;
	}
	public function values($values){
		$this->builds[] = "SET $values ";
		return $this;
	}
	public function update($table){
		$this->clean();
		$this->builds[] = "UPDATE $table";
		return $this;
	}
	public function build(){
		return implode(' ',$this->builds);
	}
}