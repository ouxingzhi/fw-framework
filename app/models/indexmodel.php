<?php

namespace Models;

use Fw\Core\Model;

class IndexModel extends Model{
	public function getDbSession(){

	}
	/**
	 * get table schema
	 * @return {array}
	 */
	public function getSchema(){
		return array(
			static::__TABLE=>'message',
			static::__PK=>'ID',
			static::__FIELD=> array(
				'ID'=>'int',
				'USER'=>'date|now()',
				'TITLE'=>'string',
				'MESSAGE'=>'string'
			)
		);
	}
}