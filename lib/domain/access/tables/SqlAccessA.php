<?php
/**
 * the query compiler/executer object
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @version 0.0.1
 */
namespace orm\domain\access\tables;
use orm\domain\access\connections\ConnectionFactory;
use orm\domain\access\drivers\MySqlDriver;
use orm\domain\access\drivers\PostgreSqlDriver;
use orm\domain\access\drivers\sqlDriverA;
use orm\domain\access\drivers\SqlGenericDriver;
use orm\domain\connections\ConnectionA;
use orm\domain\connections\ConnectionType;
use orm\domain\domain\DomainObjectA;
use orm\domain\ExceptionInvalidType;
use vsc\infrastructure\Object;

abstract class SqlAccessA extends Object implements SqlAccessI {
	/**
	 * @var ConnectionA
	 */
	private $oConnection;
	/**
	 * var sqlDriverA
	 */
	private $oDriver;

	public function __construct () {}

	abstract public function getDatabaseType();
	abstract public function getDatabaseHost();
	abstract public function getDatabaseUser();
	abstract public function getDatabasePassword();
	abstract public function getDatabaseName();

	/**
	 * @param ConnectionA $oConnection
	 * @return void
	 */
	public function setConnection ( ConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}

	/**
	 * @throws ExceptionInvalidType
	 * @return ConnectionA
	 */
	public function getConnection () {
		if (!self::isValidConnection($this->oConnection)) {
			$this->setConnection( ConnectionFactory::connect(
				$this->getDatabaseType(),
				$this->getDatabaseHost(),
				$this->getDatabaseUser(),
				$this->getDatabasePassword()
			));

			$this->getConnection()->selectDatabase($this->getDatabaseName());

			if (!self::isValidConnection($this->oConnection)) {
				throw new ExceptionInvalidType ('Could not establish a valid DB connection - current resource type [' . get_class ($this->oConnection) . ']');
			}
		}
		return $this->oConnection;
	}

	public function getDriver () {
		if (!sqlDriverA::isValid($this->oDriver)) {
			$iDatabaseType = $this->getDatabaseType();
			switch ($iDatabaseType) {
				case ConnectionType::postgresql:
					$this->oDriver = new PostgreSqlDriver();
					break;
				case ConnectionType::mysql:
					$this->oDriver = new MySqlDriver();
					break;
				case ConnectionType::sqlite:
				default:
					$this->oDriver = new SqlGenericDriver();
					break;
			}
		}
		return $this->oDriver;
	}

	static public function isValidConnection ($oConnection) {
		if ($oConnection instanceof ConnectionA) {
			return ConnectionFactory::validType ($oConnection->getType());
		} else {
			return false;
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see lib/domain/access/SqlAccessI#insert()
	 */
	public function insert ( DomainObjectA $oDomainObject) {
		return $this->getConnection()->query($this->outputInsertSql($oDomainObject));
	}

	/**
	 * (non-PHPdoc)
	 * @see SqlAccessI::update()
	 */
	public function update ( DomainObjectA $oDomainObject) {
		return $this->getConnection()->query($this->outputUpdateSql($oDomainObject));
	}

	/**
	 * (non-PHPdoc)
	 * @see SqlAccessI::delete()
	 */
	public function delete ( DomainObjectA $oDomainObject) {
		return $this->getConnection()->query($this->outputDeleteSql($oDomainObject));
	}

	/**
	 * (non-PHPdoc)
	 * @see SqlAccessI::save()
	 */
	public function save ( DomainObjectA $oDomainObject) {
		//return $this->getConnection()->query($this->outputDeleteSql($oDomainObject));
	}
}
