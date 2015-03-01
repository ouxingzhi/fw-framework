<?php

namespace Fw\Core;

use Fw\Db\SqlBuild;
/**
 * Table基类
 */

abstract class Table{

	const TYPE_INT = 'int';

	const TYPE_STRING = 'string';

	const TYPE_DATE = 'date';

	const TYPE_DATETIME = 'datetime';

	const __TABLE = '$TABLE';

	const __PK = '$PK';

	const __FIELD = '$FIELD';
    
    const __AND = 'and';
    
    const __OR = 'or';

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
    
    public function insert($values,$keys=array()){
        $sql = new SqlBuild();
        $sql->insert($this->getTable());
        if(!empty($keys)){
            $info = static::buildSqlValues($keys,$values);
            $sql->values($info['key'],$info['value']);
        }else{
            $sql->set(static::buildSqlSets($values));
        }
        $this->last_sql = $sql->build();
        
        $db = $this->getDbSession();
        $db->query($this->last_sql);
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
    public function update($values,$wheres){
        $sql = new SqlBuild();
        $sql->update($this->getTable())
            ->set(static::buildSqlSets($values))
            ->where(static::buildSqlConds($wheres));
        $this->last_sql = $sql->build();
        $db = $this->getDbSession();
		$db->query($this->last_sql);
        return $this;
    }
	public function find($fields=array(),$where=array(),$order="",$limit=""){
		$sql = new SqlBuild();
		$sql->select($fields)
			->from($this->getTable());
		if(!empty($where)){
			$sql->where(static::buildSqlConds($where));
		}
        
		if(!empty($order)){
			$sql->order($order);
		}
        if(!empty($limit)){
            if(is_array($limit)){
                $limit = implode(',',array_slice($limit,0,2));   
            }
            $sql->limit($limit);   
        }
		$this->last_sql = $sql->build();
		$db = $this->getDbSession();

		return $db->query($this->last_sql);
	}
    public function findOne($fields=array(),$where=array(),$order=""){
        $result = $this->find($fields,$where,$order,"1");
        if(empty($result)) return null;
        return $result[0];
    }
    public function countFind($where=array(),$order="",$limit=""){
        $sql = new SqlBuild();
		$sql->select($this->getPK())
			->from($this->getTable());
		if(!empty($where)){
			$sql->where(static::buildSqlConds($where));
		}
        
		if(!empty($order)){
			$sql->order($order);
		}
        if(!empty($limit)){
            if(is_array($limit)){
                $limit = implode(',',array_slice($limit,0,2));   
            }
            $sql->limit($limit);   
        }
		$this->last_sql = $sql->build();
		$db = $this->getDbSession();

		return $db->countQuery($this->last_sql);
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
    public function getLastInsertId(){
        $db = $this->getDbSession();
        return $db->getLastInsertId();
    }

	public function buildSqlValuesSetString(){
		return $this->buildSqlSets($this->data);
	}
    public function buildSqlSets($sets=array()){
        $values = array();
		$schema = $this->getSchema();
		$fields = $schema[static::__FIELD];
		foreach($fields as $field=>$define){
			$def = explode('|',$define);
			$type = strtolower($def[0]);
			$defval = isset($def[1]) ? strtolower($def[1]) : null;

			if(isset($sets[$field])){
				$values[] = static::buildSqlValueItem($field,$sets[$field],$type,$defval);
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
				if(!is_array($val)){
					$builds[] = $val . '';
				}else{
					$builds[] = '(' . static::buildSqlConds($val) . ')';
				}
			}else{
				$builds[] = "`$key`=\"$val\"";
			}
		}
		return implode(' ',$builds); 
	}
	private static function buildSqlValueItem($field,$value,$type){

		switch($type){
			case static::TYPE_DATE:
			case static::TYPE_DATETIME:
				if(gettype($value) === 'integer'){
					$str = "\"" . date($type === static::TYPE_DATE ? 'Y-m-d' : 'Y-m-d h:i:s',$value) ."\"";
				}else{
					$str = "\"" . mysql_real_escape_string($value) . "\"";
				}
				break;
			
			default:
				$str = "\"" . mysql_real_escape_string($value) . "\"";
				break;
		}
		return '`' . $field . '`' . '=' . $str;
	}
    private static function buildSqlValues($keys,$values){
        $key = implode('`,`',$keys);
        if($key){ $key = '(`' . $key . '`)'; }
        $value = array();
        foreach($values as $i=>$val){
            if(!empty($val)){
                $value[] = '("' . implode('","',$val) . '")';
            }
        }
        return array(
            'key'=>$key,
            'value'=>implode(',',$value)
        );
    }
	private static function buildSqlDefaultValue($field,$type,$defval){
		$reg = '/^([a-z]\w+)\(([^\(\)]*)\)$/im';
		$fnname = null;
		$fnparam = null;
		if(preg_match($reg,$defval,$matchs)){
			$fnname = $matchs[1];
			$fnparam = $matchs[2];
		}
		switch($type){
			
			case static::TYPE_DATE:
				$str = $fnname ? static::datefilter($fnname,$fnparam) : "\"$defval\"";
				break;
			case static::TYPE_DATETIME:
				$str = $fnname ? static::datetimefilter($fnname,$fnparam) : "\"$defval\"";
				break;
			case static::TYPE_INT:
			default:
				$str = "\"" . ($fnname ? $fnparam : $defval) . "\"";
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