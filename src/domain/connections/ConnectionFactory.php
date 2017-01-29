<?php
/**
 * Factory class for data objects
 */
namespace orm\domain\connections;

use orm\domain\access\drivers\SqlGenericDriver;
use vsc\ExceptionUnimplemented;
use vsc\infrastructure\vsc;

class ConnectionFactory {
	static private	$instance	= null;

	/**
	 * returning if the set DB type is supported
	 *
	 * @param string $iConnectionType
	 * @return bool
	 */
	public static function validType ($iConnectionType) {
		$oReflectedSelf = new \ReflectionClass(new ConnectionType());
		return in_array ($iConnectionType, $oReflectedSelf->getConstants());
	}

	/**
	 * @param int $iConnectionType
	 * @param string $dbHost
	 * @param string $dbUser
	 * @param string $dbPass
	 * @param string $dbName
	 * @return null|MySql|MySql|PostgreSql|NullSql
	 * @throws \vsc\ExceptionUnimplemented
	 */
	static public function getInstance ($iConnectionType, $dbHost = null, $dbUser = null, $dbPass = null, $dbName = null) {
		if (!self::validType ($iConnectionType)) {
			self::$instance = new NullSql();
			throw new ExceptionUnimplemented ('The database type is invalid');
		}

		if(!ConnectionA::isValid(self::$instance)) {
			switch ($iConnectionType) {
			case ConnectionType::mysql:
				if (extension_loaded('mysqli')) {
					self::$instance =  new MySql($dbHost, $dbUser, $dbPass, $dbName);
				} elseif (extension_loaded('mysql')) {
					self::$instance =  new MySql($dbHost, $dbUser, $dbPass, $dbName);
				} else {
					self::$instance = new NullSql(); // Sql server not implemented
				}
				break;
			case ConnectionType::postgresql:
				self::$instance = new PostgreSql ($dbHost, $dbUser, $dbPass, $dbName);
				break;
			case ConnectionType::sqlite:
			case ConnectionType::mssql:
				self::$instance = new NullSql (); // Sql server not implemented
				break;
			}
		}

		if (!ConnectionA::isValid(self::$instance) || self::$instance->error) {
			self::$instance = new NullSql ();
		}

		return self::$instance;
	}

	/**
	 * returns the current instance of the DB connection
	 * or a new connection of type $incString
	 *
	 * @param int $iConnectionType
	 * @param string $dbHost
	 * @param string $dbUser
	 * @param string $dbPass
	 * @param string $dbName
	 * @return SqlGenericDriver
	 */

	static public function connect($iConnectionType, $dbHost = null, $dbUser = null, $dbPass = null, $dbName = null) {
		self::$instance = self::getInstance($iConnectionType, $dbHost, $dbUser, $dbPass, $dbName);

		return self::$instance;
	}
}
