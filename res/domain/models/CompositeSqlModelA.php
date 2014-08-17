<?php
/**
 * @pacakge domain
 * @subpackage models
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.05.20
 */
namespace orm\domain\models;

use orm\domain\access\tables\SqlAccess;
use orm\domain\connections\ConnectionA;
use orm\domain\domain\CompositeDomainObjectI;
use orm\domain\domain\DomainObjectA;
use orm\domain\domain\fields\FieldA;
use orm\domain\domain\indexes\IndexA;

abstract class CompositeSqlModelA extends SimpleSqlModelA implements CompositeDomainObjectI {
	private $oConnection;
	private $oHelper;

	private $aDomainLinks;

	public function getDomainObjects () {
		$oRef = new \ReflectionObject($this);
		$aRet = array();
		$aProperties = $oRef->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PRIVATE);
		/* @var \ReflectionProperty $oProperty */
		foreach ($aProperties as $oProperty) {
			if (!$oProperty->isPrivate()) {
				$oValue = $oProperty->getValue($this);
			} else {
				$oProperty->setAccessible(true);
				$oValue = $oProperty->getValue($this);
				$oProperty->setAccessible(false);
			}
			if ( DomainObjectA::isValid($oValue)) {
				$aRet[$oProperty->getName()] = $oValue;
			}
		}
		return $aRet;
	}

	public function __get ($sName) {
		$aDomainObjects =  $this->getDomainObjects();
		return $aDomainObjects[$sName];
	}

	public function getDomainObjectRelations () {}

	public function setConnection ( ConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}

	public function getConnection () {
		return $this->oConnection;
	}

	public function __init(){
// 		$this->oConnection = ConnectionFactory::connect(
// 			$this->getDatabaseType(),
// 			$this->getDatabaseHost(),
// 			$this->getDatabaseUser(),
// 			$this->getDatabasePassword()
// 		);

// 		$this->oConnection->selectDatabase($this->getDatabaseName());
	}

	//	abstract protected function buildObject();
	/*
	abstract public function getDatabaseType();
	abstract public function getDatabaseHost();
	abstract public function getDatabaseUser();
	abstract public function getDatabasePassword();
	abstract public function getDatabaseName();
	*/

	public function addJoin ( DomainObjectA $oRightObj, FieldA $oRightField, DomainObjectA $oLeftObj, FieldA $oLeftField) {
		$oRightObj->setTableAlias('t1');
		$oLeftObj->setTableAlias('t2');
	}

	/**
	 *
	 * @param DomainObjectA $oChild
	 * @return bool
	 */
	public function loadChild ( DomainObjectA $oChild) {}

	/**
	 * @todo Finish IT !!
	 * @param DomainObjectA $oChild
	 * @return bool
	 */
	public function join ( DomainObjectA $oObject) {
		$this->addFields ($oObject->getFields (), $oObject->getTableName());

		return $this;
	}

//	public function getTableName() {}

	/**
	 * @param FieldA[] $aFields
	 * @param string $sAlias
	 * @return void
	 */
	public function addFields ($aFields, $sAlias) {}

	/**
	 * @param array $aIncField
	 * @return void
	 */
	public function addField ($aIncField) {}

	/**
	 * @return FieldA[]
	 */
	public function getFields () {
		$aReturnArray = array();
		foreach ($this->getDomainObject() as $oDomain) {
			$aReturnArray = array_merge ($aReturnArray, $oDomain->getFields());
		}

		return $aReturnArray;
	}

	protected function getPropertyNames ($bAll = false) {
		return $this->getFieldNames();
	}

	/**
	 * gets all the column names as an array
	 * @return string[]
	 */
	public function getFieldNames($bWithAlias = false) {
		$aRet = array();
		/* @var DomainObjectA $oDomainObject */
		foreach ($this->getDomainObjects() as $oDomainObject) {
			$aRet = array_merge ($aRet, $oDomainObject->getFieldNames(true));
		}

		return $aRet;
	}

	public function addIndex ( IndexA $oIndex) {}

	public function getIndexes ($bWithPrimaryKey = false) {}

	public function getById ($iId) {
		$a = new SqlAccess();
		$a->setConnection($this->getConnection());

		d ($a->outputSelectSql($this->getDomainObjects()));
	}

	public function getForeignKeys () {}
}

