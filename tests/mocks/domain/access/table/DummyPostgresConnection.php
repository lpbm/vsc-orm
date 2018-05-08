<?php
namespace mocks\domain\access\table;

use mocks\domain\connections\DummyConnection;
use orm\domain\access\tables\SqlAccessA;
use orm\domain\connections\ConnectionType;
use orm\domain\connections\NullSql;
use vsc\ExceptionUnimplemented;

class DummyPostgresConnection extends SqlAccessA {

	public function getDatabaseType()
	{
		return ConnectionType::postgresql;
	}

	public function getDatabaseHost()
	{
		return 'localhost';
	}

	public function getDatabaseUser()
	{
		return '';
	}

	public function getDatabasePassword()
	{
		return '';
	}

	public function getDatabaseName()
	{
		return '';
	}

	public function getConnection () {
		try {
			$o = parent::getConnection();
		} catch (ExceptionUnimplemented $e) {
			$o = new DummyConnection();
		}
		return $o;
	}
}
