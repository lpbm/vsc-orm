<?php
namespace orm\domain\access\connections;

use vsc\ExceptionUnimplemented;

abstract class postgreSql extends ConnectionA {
	public 		$conn,
				$link;

	static public function isValid ($oLink) {
		return !is_null($oLink) && pg_connection_status($oLink) !== PGSQL_CONNECTION_OK;
	}

	public function isConnected () {
		return (pg_connection_status($this->link) !== PGSQL_CONNECTION_OK);
	}

	abstract protected function getDatabaseType();

	abstract protected function getDatabaseHost();

	abstract protected function getDatabaseUser();

	abstract protected function getDatabasePassword();

	abstract protected function getDatabaseName();

	public function __construct( $dbHost = null, $dbUser = null, $dbPass = null , $dbName = null ){
		if (!extension_loaded('pgsql')) {
			throw new ExceptionConnection ('PostgreSQL extension is not loaded.');
		}
		if ( empty ($dbHost) ) {
			if ( is_null ($this->getDatabaseHost()) ) {
				throw new ExceptionConnection ('Database connection data missing: [DB_HOST]');
			} else {
				$dbHost = $this->getDatabaseHost();
			}
		}

		if ( empty ($dbUser) ) {
			if ( is_null ($this->getDatabaseUser()) ) {
				throw new ExceptionConnection ('Database connection data missing: [DB_USER]');
			} else {
				$dbUser = $this->getDatabaseUser();
			}
		}

		if ( empty($dbPass) ) {
			if ( is_null ($this->getDatabasePassword()) ) {
				throw new ExceptionConnection ('Database connection data missing: [DB_PASS]');
			} else {
				$dbPass = $this->getDatabasePassword();
			}
		}

		if ( empty($dbName) ) {
			if (is_null ($this->getDatabaseName()) ) {
				throw new ExceptionConnection ('Database connection data missing: [DB_NAME]');
			} else {
				$dbName = $this->getDatabaseName();
			}
		}

		try {
			$this->connect ($dbHost, $dbUser, $dbPass, $dbName);
		} catch (Exception $e) {
			_e($e);
		}
	}

	public function getType () {
		return ConnectionType::postgresql;
	}

	public function validResult ($oResource) {
		return (is_resource($oResource) && get_resource_type($oResource) == 'pgsql result');
	}

	/**
	 * wrapper for pg_connect
	 *
	 * @return bool
	 */
	protected function connect ($dbHost = null, $dbUser = null, $dbPass = null , $dbName = null ) {
		try {
			$this->link	= pg_connect('host='.$dbHost.' user='. $dbUser.' password='.$dbPass . (!empty ($dbName) ? 'dbname='.$dbName : '' ));
		} catch (ErrorException $e) {
			$this->error = $e->getMessage();
			trigger_error ($this->error, E_USER_ERROR);
		}
		if (is_resource($this->link)) {
			$err = pg_last_error($this->link);
			if ($err) {
				$this->error = $err;
				trigger_error ($this->error, E_USER_ERROR);
				return false;
			}
		}
		return true;
	}

	/**
	 * wrapper for pg_close
	 *
	 * @return bool
	 */
	public function close (){
		pg_close($this->link);
		$this->link = null;
		return true;
	}

	/**
	 * @param string $incData
	 * @return bool
	 */
	public function selectDatabase ($incData){
		// in postgres we don't quite need it as pg_connect handles it
		return true;
	}

	/**
	 * wrapper for pg_escape_string
	 *
	 * @param mixed $incData
	 * @return mixed
	 */
	public function escape ($incData){
		// so far no escaping on BYTEA fields
		// also there's a problem with the fact that I use tdoAbstract->escape
		// to enclose values in quotes for MySQL
		// TODO - this fracks up the postgres stuff
		if (is_null ($incData)){
			return 'NULL';
		} elseif (is_int($incData)) {
			return intval($incData);
		} elseif (is_float($incData)) {
			return floatval($incData);
		} elseif (is_string($incData)) {
			return "'" . pg_escape_string ($this->link, $incData) . "'";
		}
	}

	/**
	 * wrapper for mysql_query
	 *
	 * @param string $query
	 * @return mixed
	 */
	public function query ($query){
		if ($this->isConnected()) {
			return false;
		}
		if (!empty($query)) {
			$qst = microtime(true);
			$this->conn = pg_query($this->link, $query);
			$qend = microtime(true);
			if (!isset($GLOBALS['queries'])) {
				$GLOBALS['queries'] = array ();
			}
			if (isset($GLOBALS['queries'])) {
				$aQuery = array (
					'query'		=> $query,
					'duration'	=> $qend - $qst, // seconds
				);

				$GLOBALS['queries'][] = $aQuery;
			}
		} else
			return false;

		if (pg_errormessage($this->link))	{
			$e = new ExceptionDomain($this->link->error.'<br/> ' . $query, $this->link->errno);
			return false;
		}

		if (stristr($query, 'select')) {
			return $this->conn;
		} elseif ( preg_match('/insert|update|replace|delete/i', $query) ) {
			return pg_affected_rows ($this->conn);
		}
	}

	/**
	 * wrapper for mysql_fetch_row
	 *
	 * @return array
	 */
	public function getRow (){
		if ($this->conn)
			return pg_fetch_row ($this->conn);
	}

	// FIXME: for some reason the getAssoc and getArray work differently
	public function getAssoc () {
		if ($this->conn)
			return pg_fetch_assoc ($this->conn);
	}

	/**
	 * wrapper for mysql_fetch_row
	 *
	 * @return array
	 */
	public function getObjects () {
		// pg_??
		$retArr = array ();
//		$i = 0;
//		if ($this->conn && $this->link) {
//			while ($i < mysqli_field_count ($this->link)) {
//				$t = $this->conn->fetch_field_direct ($i++);
//				$retArr[] = $t;
//			}
//		}

		return $retArr;
	}

	/**
	 * wrapper for mysql_fetch_assoc
	 *
	 * @return array
	 */
	public function getArray (){
		$retArr = array();
		if ($this->conn)
			while ( ($r = pg_fetch_assoc ($this->conn)) ){
				$retArr[] = $r;
			}

		return $retArr;
	}

	/**
	 * getting the first result in the resultset
	 *
	 * @return mixed
	 */
	public function getScalar() {
		$retVal = $this->getRow();
		if (is_array($retVal))
			$retVal = current($retVal);
		return $retVal;
	}


	public function startTransaction ($bAutoCommit = false) {
		throw new ExceptionUnimplemented('Transaction support for postgres is not currenty implemented');
	}

	public function rollBackTransaction () {
		throw new ExceptionUnimplemented('Transaction support for postgres is not currenty implemented');
	}

	public function commitTransaction () {
		throw new ExceptionUnimplemented('Transaction support for postgres is not currenty implemented');
	}
}
