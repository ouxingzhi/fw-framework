<?php

namespace Fw\Table;

use Fw\Core\Table;
use Fw\Db\Mysql as DBMysql;
use Fw\Config\Config;


abstract class Mysql extends Table{
	private $dbsession;
	public function getDbSession(){
		if($this->dbsession) return $this->dbsession;
		$config = Config::get("database.mysql") or array();
		return $this->dbsession = new DBMysql($config);
	}
}