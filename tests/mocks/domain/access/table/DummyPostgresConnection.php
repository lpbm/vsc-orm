<?php
namespace mocks\domain\access\table;

use orm\domain\access\AccessA;
use orm\domain\access\tables\SqlAccessA;
use orm\domain\connections\ConnectionType;

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
}
