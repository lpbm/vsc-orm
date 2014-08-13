<?php
/**
 * Pseudo null to be implemented (ehm, inherited) by the rest
 * of the DB classes.
 */
namespace orm\domain\access\connections;

use orm\domain\connections\ConnectionA;
use orm\domain\connections\ConnectionType;
use vsc\infrastructure\String;
use vsc\ExceptionUnimplemented;

class nullSql extends ConnectionA {
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
}
