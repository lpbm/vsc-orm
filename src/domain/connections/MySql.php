<?php
/**
 * At the moment we only have the habsql class:D, but:
 * Here should be a _PACKAGE_ to include:
 * <type>Sql - class to encapsulate the <something>sql_* functionality
 * 			 - it will be derived from tdoHabstract
 * <type>SqlR - the sql resource of type <type> [might not be needed]
 * 			   - in case I need it, <type>Sql->conn will have this type
 * <type>SqlOrder - a struct(class, yeah, yeah) to contain the ORDER BY
 * 					pairs of stuff: string $field, bool $ASC = true
 * <type>SqlJoin - class to handle joining of two <type>Sql classes
 * 				  - TODO: very important
 * <type>SqlField
 *
 * OBS: maybe the static methods (_AND, _OR, sa.) can be conained into
 *  an external object. (??!)
 */
namespace orm\domain\connections;

use vsc\ExceptionUnimplemented;
use vsc\infrastructure\StringUtils;

class MySql extends ConnectionA {
	/**
	 * @var \mysqli_result
	 */
	public 		$conn;

	/**
	 * @var \mysqli
	 */
	public		$link;
	private 	$iLastInsertId;

	private		$defaultSocketPath;

	static public function isValidLink ($oLink) {
		return ($oLink instanceof \mysqli);
	}

	public function isConnected () {
		return (!is_null($this->link->connect_error));
	}

	public function __construct( $dbHost = null, $dbUser = null, $dbPass = null, $dbName = null ){
		if (!extension_loaded('mysqli')) {
			throw new ExceptionConnection ('MySQL extension is not loaded.');
		}
		parent::__construct ( $dbHost, $dbUser, $dbPass);
		$this->connect ($dbHost, $dbUser, $dbPass, $dbName);
	}

	public function getEngine () {
		return 'InnoDB';
	}

	public function getType () {
		return ConnectionType::mysql;
	}

	public function validResult ($oResource) {
		return ($oResource instanceof \mysqli_result);
	}

	/**
	 * wrapper for mysql_connect
	 *
	 * @param string $dbHost
	 * @param string $dbUser
	 * @param string $dbPass
	 * @param string $dbName
	 * @throws \orm\domain\connections\ExceptionConnection
	 * @return bool
	 */
	protected function connect ($dbHost = null, $dbUser = null, $dbPass = null, $dbName = null ) {
		$this->link	= new \mysqli($dbHost, $dbUser, $dbPass, $dbName, null, $this->defaultSocketPath);
		if (!empty($this->link->connect_errno)) {
			$this->error = $this->link->connect_errno . ' ' . $this->link->connect_error;
			throw new ExceptionConnection('mysqli : ' . $this->error);
		}
		return true;
	}

	/**
	 * wrapper for mysql_close
	 *
	 * @return bool
	 */
	public function close (){
		if (self::isValidLink($this->link))
			$this->link->close ();
		// dunno how smart it is to nullify an mysqli object
		$this->link = null;
		return true;
	}

	/**
	 * wrapper for mysql_select_db
	 *
	 * @param string $incData
	 * @throws \orm\domain\connections\ExceptionConnection
	 * @return bool
	 */
	public function selectDatabase ($incData){
		if (self::isValidLink($this->link) && $this->link->select_db($incData)) {
			return true;
		} else {

			throw new ExceptionConnection($this->error);
		}
	}

	/**
	 * wrapper for mysql_real_escape_string
	 *
	 * @param mixed $incData
	 * @return mixed
	 */
	public function escape ($incData){
		if (is_null ($incData)){
			return 'NULL';
		} elseif (is_int($incData)) {
			return intval($incData);
		} elseif (is_float($incData)) {
			return floatval($incData);
		} elseif (is_string($incData)) {
			return "'" . $this->link->escape_string($incData) . "'";
		}
	}

	public function getLastInsertId() {
		return $this->iLastInsertId;
	}

	/**
	 * wrapper for mysql_query
	 *
	 * @param string $query
	 * @throws \orm\domain\connections\ExceptionConnection
	 * @return mixed
	 */
	public function query ($query){
		if (!($this->link instanceof \mysqli)) {
			return false;
		}
		if (!empty($query)) {
			$qst = microtime(true);
			$this->conn = $this->link->query($query);
			$qend = microtime(true);
		} else
			return false;

		if ($this->link->errno)	{
			throw new ExceptionConnection ($this->link->error. StringUtils::nl() . '<pre>' . $query . '</pre>' . StringUtils::nl ());
		}

		$iReturn =  $this->link->affected_rows;

		if (isset($GLOBALS['vsc::queries'])) {
			$aQuery = array (
				'query'		=> $query,
				'duration'	=> $qend - $qst,  // seconds
				'num_rows'	=> is_numeric($iReturn) ? $iReturn : 0
			);

			$GLOBALS['vsc::queries'][] = $aQuery;
		}

		if (stristr ($query, 'insert')) {
			$this->conn = $this->link->query('select last_insert_id();');
			$this->iLastInsertId = $this->getScalar();
		}

		return $iReturn;
	}

	/**
	 * wrapper for mysql_fetch_row
	 *
	 * @return array
	 */
	public function getRow (){
		if ($this->conn instanceof \mysqli_result)
			return $this->conn->fetch_row ();
	}

	// FIXME: for some reason the getAssoc and getArray work differently
	public function getAssoc () {
		if (
			$this->conn instanceof \mysqli_result
		) {
			return $this->conn->fetch_assoc();
		}
	}

	/**
	 * wrapper for mysql_fetch_row
	 *
	 * @return array
	 */
	public function getObjects () {
		$retArr = array ();
		$i = 0;
		if ($this->conn instanceof \mysqli_result && $this->link instanceof \mysqli ) {
			while ($i < mysqli_field_count ($this->link)) {
				$t = $this->conn->fetch_field_direct ($i++);
				$retArr[] = $t;
			}
		}

		return $retArr;
	}

	/**
	 * wrapper for mysql_fetch_assoc
	 *
	 * @return array
	 */
	public function getArray (){
		$retArr = array();
// 		d ($this->conn->field_count);
		if ($this->conn instanceof \mysqli_result) {
			while (($r = $this->conn->fetch_assoc ())){
				$retArr[] = $r;
			}
			$this->conn->free_result();
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
		if ($this->getEngine() != 'InnoDB')
		throw new ExceptionUnimplemented ('Unable to use transactions for the current MySQL engine ['.$this->getEngine().'].');

		$sQuery = 'SET autocommit=' . ($bAutoCommit ? 1 : 0) . ';';
		$this->query($sQuery);
		$sQuery = 'START TRANSACTION;';
				return $this->query($sQuery);
	}

	public function rollBackTransaction () {
		if ($this->getEngine() != 'InnoDB')
		throw new ExceptionUnimplemented ('Unable to use transactions for the current MySQL engine ['.$this->getEngine().'].');

		$sQuery = 'ROLLBACK;';
		return $this->query($sQuery);
	}

	public function commitTransaction () {
		if ($this->getEngine() != 'InnoDB')
		throw new ExceptionUnimplemented ('Unable to use transactions for the current MySQL engine ['.$this->getEngine().'].');

		$sQuery = 'COMMIT;';
				return $this->query($sQuery);
	}

	public function getFirst()
	{
		// TODO: Implement getFirst() method.
	}
}
