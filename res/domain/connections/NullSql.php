<?php
/**
 * Pseudo null to be implemented (ehm, inherited) by the rest
 * of the DB classes.
 */
namespace orm\domain\connections;

use vsc\infrastructure\String;
use vsc\ExceptionUnimplemented;

class NullSql extends ConnectionA {
	public 		$conn,
				$link;

	/**
	 * just a function to trigger an error in the eventuality of using
	 * an unsupported DB_TYPE connection (usually because of a config error)
	 *
	 * TODO: this can be done more elegantly using an exception in the
	 *         @var ConnectionFactory class
	 *
	 * @param string $dbHost
	 * @param string $dbUser
	 * @param string $dbPass
	 * @throws \vsc\ExceptionUnimplemented
	 */
	public function __construct( $dbHost = null, $dbUser = null, $dbPass = null ) {
		throw new ExceptionUnimplemented('This site has all database functionality disabled.'.String::nl().' Please check for configuration errors.');
	}

	public function getType () {
		return ConnectionType::nullsql;
	}

	public function close () {}

	public function getScalar () {}

	public function startTransaction ($bAutoCommit = false) {}

	public function rollBackTransaction () {}

	public function commitTransaction () {}

	protected function connect()
	{
		// TODO: Implement connect() method.
	}

	public function escape($incData)
	{
		// TODO: Implement escape() method.
	}

	public function query($query)
	{
		// TODO: Implement query() method.
	}

	public function getRow()
	{
		// TODO: Implement getRow() method.
	}

	public function getArray()
	{
		// TODO: Implement getArray() method.
	}

	public function getFirst()
	{
		// TODO: Implement getFirst() method.
	}
}
