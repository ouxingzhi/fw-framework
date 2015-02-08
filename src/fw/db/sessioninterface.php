<?php

namespace Fw\Db;

interface SessionInterface{
	public function open();

	public function close();

	public function isOpen();

	public function query($querystr);

	public function getLastInsertId();
}