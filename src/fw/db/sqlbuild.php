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
	public function select($fields){
		$this->clean();
		$fieldsstr = '*';
		if(is_array($fields) and !empty($fileds)){
			$fieldsstr = implode(',',$fields);
		}elseif(is_string($fields) and !empty($fileds)){
            $fieldsstr = $fields;
        }
		$this->builds[] = "SELECT " . $fieldsstr;
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
		$this->builds[] = "LIMIT $limit";
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
	public function set($values){
		$this->builds[] = "SET $values ";
		return $this;
	}
    public function values($keys,$values){
        $this->builds[] = "$keys VALUES $values";
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