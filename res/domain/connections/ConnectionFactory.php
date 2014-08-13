<?php
/**
 * Factory class for data objects
 */
namespace orm\domain\access\connections;

use orm\domain\access\drivers\PostgreSqlDriver;
use orm\domain\access\drivers\SqlGenericDriver;
use orm\domain\connections\ConnectionA;
use orm\domain\connections\ConnectionType;
use vsc\ExceptionUnimplemented;
class ConnectionFactory {
	static private	$instance	= null;

	/**
	 * returning if the set DB type is supported
	 *
	 * @param string $iConnectionType
	 * @return bool
	 */
	public static function validType ($iConnectionType) {
		$oReflectedSelf = new \ReflectionClass('ConnectionType');
		return in_array ($iConnectionType, $oReflectedSelf->getConstants());
	}

	static public function getInstance ($iConnectionType, $dbHost = null, $dbUser = null, $dbPass = null, $dbName = null) {
		if (!self::validType ($iConnectionType)) {
			self::$instance = new nullSql();
			throw new ExceptionUnimplemented ('The database type is invalid');
		}

		if(!ConnectionA::isValid(self::$instance)) {
			switch ($iConnectionType) {
			case ConnectionType::mysql:
				if (extension_loaded('mysqli')) {
					self::$instance =  new MySqlIm($dbHost, $dbUser, $dbPass, $dbName);
				} elseif (extension_loaded('mysql')) {
					self::$instance =  new MySql($dbHost, $dbUser, $dbPass, $dbName);
				} else {
					self::$instance = new nullSql(); // Sql server not implemented
				}
				break;
			case ConnectionType::postgresql:
				self::$instance = new PostgreSql ($dbHost, $dbUser, $dbPass, $dbName);
				break;
			case ConnectionType::sqlite:
			case ConnectionType::mssql:
				self::$instance = new nullSql (); // Sql server not implemented
				break;
			}
		}

		if (!ConnectionA::isValid(self::$instance) || self::$instance->error) {
			self::$instance = new nullSql ();
		}

		return self::$instance;
	}

	/**
	 * returns the cuurent instance of the DB connection
	 * or a new connection of type $incString
	 *
	 * @param string $incString
	 * @return SqlGenericDriver
	 */

	static public function connect($incString, $dbHost = null, $dbUser = null, $dbPass = null, $dbName = null) {
		self::getInstance($incString, $dbHost, $dbUser, $dbPass, $dbName);

		return self::$instance;
	}
}
