<?php
namespace _fixtures\domain\access;

use orm\domain\access\AccessA;
use orm\domain\connections\ConnectionType;

class DummyConnectionAccess extends AccessA {

	protected function getDatabaseType()
	{
		return ConnectionType::postgresql;
	}

	protected function getDatabaseHost()
	{
		return 'localhost';
	}

	protected function getDatabaseUser()
	{
		return '';
	}

	protected function getDatabasePassword()
	{
		return '';
	}

	protected function getDatabaseName()
	{
		return '';
	}
}
