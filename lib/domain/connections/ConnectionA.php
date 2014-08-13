<?php
/**
 * Pseudo interface to be implemented (ehm, inherited) by the rest
 * of the DB classes.
 * @pacakge \orm\domain\connections
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
namespace orm\domain\connections;

use vsc\infrastructure\Object;

abstract class ConnectionA extends Object {
	public $conn;
	public $error;
	public $link;

	private $sDatabaseName;
	private $sDatabasePassword;
	private $sDatabaseUser;
	private $sDatabaseHost;

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
		$this->sDatabaseHost = $dbHost;
		$this->sDatabaseUser = $dbUser;
		$this->sDatabasePassword = $dbPass;
	}

	protected function getDatabaseHost() {
		return $this->sDatabaseHost;
	}

	protected function getDatabaseUser() {
		return $this->sDatabaseUser;
	}

	protected function getDatabasePassword() {
		return $this->sDatabasePassword;
	}

	protected function getDatabaseName() {
		return $this->sDatabaseName;
	}
	public function selectDatabase($dbName) {
		$this->sDatabaseName = $dbName;
	}

//	static public function isValid ($oIncomingConnection) {
//		return ($oIncomingConnection instanceof static);
//	}

	public function __destruct() {}

	abstract protected function connect();

	abstract public function getType ();

	abstract public function escape ($incData);

	abstract public function query($query);

	abstract public function getRow ();

	abstract public function getArray ();

	abstract public function getFirst ();

	abstract public function close ();

	abstract public function startTransaction ($bAutoCommit = false);

	abstract public function rollBackTransaction ();

	abstract public function commitTransaction ();
}
