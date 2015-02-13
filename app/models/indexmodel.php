<?php

namespace Models;

use Fw\Model\Mysql;

class IndexModel extends Mysql{
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