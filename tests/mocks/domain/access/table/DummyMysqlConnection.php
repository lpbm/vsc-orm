<?php
namespace mocks\domain\access\table;

use orm\domain\access\AccessA;
use orm\domain\access\tables\SqlAccess;
use orm\domain\connections\ConnectionType;

class DummyMysqlConnection extends SqlAccess {

	public function getDatabaseType()
	{
		return ConnectionType::mysql;
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
}
