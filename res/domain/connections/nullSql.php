<?php
/**
 * Pseudo null to be implemented (ehm, inherited) by the rest
 * of the DB classes.
 */

use vsc\infrastructure\String;

class nullSql extends ConnectionA {
	public 		$conn,
				$link;

	/**
	 * just a function to trigger an error in the eventuality of using
	 * an unsupported DB_TYPE connection (usually because of a config error)
	 *
	 * TODO: this can be done more elegantly using an exception in the
	 * 		 ConnectionFactory class
	 *
	 * @param string $dbHost
	 * @param string $dbUser
	 * @param string $dbPass
	 */
	public function __construct( $dbHost = null, $dbUser = null, $dbPass = null ) {
		throw new \vsc\ExceptionUnimplemented('This site has all database functionality disabled.'.String::nl().' Please check for configuration errors.');
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
