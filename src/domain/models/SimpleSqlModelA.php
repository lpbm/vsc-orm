<?php
/**
 * @pacakge domain
 * @subpackage models
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.01.03
 */
namespace orm\domain\models;

use orm\domain\connections\ConnectionA;
use orm\domain\domain\DomainObjectA;
use orm\domain\domain\DomainObjectI;
use orm\domain\domain\fields\FieldA;
use orm\domain\domain\indexes\IndexA;
use \vsc\domain\models\ModelA;
use \vsc\infrastructure\Base;

class SimpleSqlModelA extends ModelA implements DomainObjectI {
	final public function __construct () {
		$this->buildObject();
		$this->__init();
		parent::__construct ();
	}
	public function __get ($sVarName) {
		try {
			return $this->getDomainObject()->__get($sVarName);
		} catch (\Exception $e) {
			return parent::__get($sVarName);
		}
	}

	public function __set ($sVarName, $sValue) {
		try {
			return $this->getDomainObject()->$sVarName = $sValue;
		} catch (\Exception $e) {
			return parent::__set($sVarName, $sValue);
		}
	}

	public function __call ($sMethodName, $aParameters) {
		$i = preg_match ('/(set|get)(.*)/i', $sMethodName, $found );
		if ($i) {
			$sMethod	= $found[1];
			$sProperty 	= $found[2];

			$sProperty[0] = strtolower ($sProperty[0]); // lowering the first letter

			$oProperty = $this->getDomainObject()->__get($sProperty);
		} else {
			$sMethod = $sMethodName;
		}

		if ( $sMethod == 'set' && FieldA::isValid($oProperty)) {
			// check for aFields with $found[1] sTableName
			$oProperty->setValue($aParameters[0]);
			return true;
		} else if ( $sMethod == 'get' ) {
			return $oProperty;
		} else {
			return $this->getDomainObject()->$sMethod ($aParameters[0]);
		}
		return parent::__call($sMethod, $aParameters);
	}

	public function getConnection () {
		return $this->oConnection;
	}

	public function __init() {}

	protected function buildObject() {
		$this->getDomainObject()->buildObject();
	}

	/**
	 * @return DomainObjectA
	 */
	public function getDomainObject() {
		return new Base();
	}

	public function getTableName() {}

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
	public function getFieldNames ($bWithAlias = false) {}

	public function addIndex ( IndexA $oIndex) {}

	public function getIndexes ($bWithPrimaryKey = false) {}

	public function setConnection ( ConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}
//	abstract public function getDomainObjects();
}

