<?php
/**
 * the query compiler/executer object
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @version 0.0.1
 */
namespace orm\domain\access\tables;
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
				throw new InvalidTypeException ('Could not establish a valid DB connection - current resource type [' . get_class ($this->oConnection) . ']');
			}
		}
		return $this->oConnection;
	}

	public function getDriver () {
		if (!sqlDriverA::isValid($this->oDriver)) {
			$iDatabaseType = $this->getDatabaseType();
			switch ($iDatabaseType) {
				case ConnectionType::postgresql:
					$this->oDriver = new postgreSQLDriver();
					break;
				case ConnectionType::mysql:
					$this->oDriver = new mySQLDriver();
					break;
				case ConnectionType::sqlite:
				default:
					$this->oDriver = new SQLGenericDriver();
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
	 * @see lib/domain/access/SqlAccessI#update()
	 */
	public function update ( DomainObjectA $oDomainObject) {
		return $this->getConnection()->query($this->outputUpdateSql($oDomainObject));
	}

	/**
	 * (non-PHPdoc)
	 * @see lib/domain/access/SqlAccessI#delete()
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
