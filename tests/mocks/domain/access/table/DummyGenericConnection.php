<?php
namespace mocks\domain\access\table;


use orm\domain\access\tables\SqlAccess;
use orm\domain\connections\ConnectionType;

class DummyGenericConnection extends SqlAccess
{
	public function getDatabaseType() {
		return ConnectionType::nullsql;
	}
	public function getDatabaseHost() {
	}
	public function getDatabaseUser() {
	}
	public function getDatabasePassword() {
	}
	public function getDatabaseName() {
	}
}