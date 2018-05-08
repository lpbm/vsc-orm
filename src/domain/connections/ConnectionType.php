<?php
/**
 * @pacakge \orm\domain\connections
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
namespace orm\domain\connections;

class ConnectionType {
	const nullsql		= -1;
	const mysql			= 1;
	const postgresql	= 2;
	const sqlite		= 3;
	const mssql			= 4;

	static public function isValid($type) {
		$types = [
			'nullsql',
			'mysql',
			'postgresql',
			'sqlite'
		];
		return in_array(strtolower($type), $types);
	}

	static public function getType($type) {
		$type = strtolower($type);
		if ($type == 'nullsql') {
			return static::nullsql;
		}
		if ($type == 'mysql') {
			return static::mysql;
		}
		if ($type == 'postgresql') {
			return static::postgresql;
		}
		if ($type == 'sqlite') {
			return static::sqlite;
		}
	}
}
