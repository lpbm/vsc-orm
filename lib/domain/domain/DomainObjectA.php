<?php
/**
 * The abstract object entity - it represents an entry in the database
 * and it contains the structure neccessary to create the table
 *
 * @pacakge domain
 * @subpackage domain
 * @author Marius Orcsik <marius@habarnam.ro>
 * @date 09.02.26
 */

use vsc\domain\models\ModelA;;

abstract class DomainObjectA extends ModelA implements DomainObjectI {
	protected 	$sTableName;
	private 	$sTableAlias;
	private 	$oPk;
	private 	$aIndexes = array ();

	public function valid ($sName = null) {
		$aFieldNames = $this->getFieldNames();
		return in_array ($sName, $aFieldNames);
	}

	final public function __construct () {
		$this->buildObject();

		$this->setFieldsParent();

//		parent::__construct ();
	}

	abstract protected function buildObject();

	public function setFieldsParent() {
		foreach ($this->getFields() as $oField) {
			$oField->setParent ($this);
		}
	}

	public function __call ($sMethodName, $aParameters) {
		$i = preg_match ('/(set|get)(.*)/i', $sMethodName, $found );
		if ($i) {
			$sMethod	= $found[1];
			$sProperty 	= $found[2];

			$sProperty[0] = strtolower ($sProperty[0]); // lowering the first letter
		} else {
			$sMethod = $sMethodName;
		}

		if ( $sMethod == 'set' && FieldA::isValid( $this->$sProperty)) {
			// check for aFields with $found[1] sTableName
			$this->$sProperty->setValue($aParameters[0]);
			return true;
		} else if ( $sMethod == 'get' ) {
			return $this->$sProperty;
		}

		return parent::__call($sMethodName, $aParameters);
	}

	public function __set ($sIncName, $mValue) {
		$this->getField($sIncName)->setValue($mValue);
	}

	public function __get ($sIncName) {
		return $this->getField($sIncName);
	}

	/**
	 * @param string $sAlias
	 * @return void
	 */
	public function setTableAlias ($sAlias) {
		$this->sTableAlias = $sAlias;
		foreach ($this->getFields() as $oField) {
			$oField->setAlias($sAlias . ':' . $oField->getName());
		}
	}

	/**
	 * @return string
	 */
	public function getTableAlias () {
		return $this->sTableAlias;
	}

	public function hasTableAlias () {
		return ($this->sTableAlias != '');
	}

	/**
	 * @param string $sName
	 * @return void
	 */
	protected function setTableName ($sName) {
		$this->sTableName = $sName;
	}

	public function getTableName () {
		return $this->sTableName;
	}

	/**
	 * @param FieldA $oIndex
	 * @return void
	 */
	public function setPrimaryKey () {
		$this->oPk = new KeyPrimary (func_get_args());
	}

	public function getPrimaryKey () {
		return $this->oPk;
	}

	public function hasPrimaryKey () {
		return ($this->oPk instanceof KeyPrimary);
	}

	/**
	 * @param FieldA[] $aFields
	 * @param string $sAlias
	 * @return void
	 */
	public function addFields ($aFields, $sAlias) {
		foreach ($aFields as $sFieldName => $oField) {
			$this->addField (array ($sAlias . '.' . $sFieldName => $oField));
		}
	}

	/**
	 * @param FieldA $aIncField
	 * @return void
	 */
	public function addField ( FieldA $oIncField) {
		$aFields = $this->getFields();
		if (!key_exists($sName, $aFields)) {
			$oIncField->setParent($this);
			$oRef = new ReflectionProperty($this, $oIncField->getName());
			$oRef->setValue($object, $oIncField);

			$oRef->setAccessible(false);
		}
	}

	protected function getField ($sName) {
		$aFields = $this->getFields();
		if (key_exists($sName, $aFields)) return $aFields[$sName];

		return false;
	}

	/**
	 * @return FieldA[]
	 */
	public function getFields () {
		$oRef = new ReflectionObject($this);
		$aProperties = $oRef->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PRIVATE);
		$aRet = array();

		/* @var $oProperty ReflectionProperty */
		foreach ($aProperties as $oProperty) {
			if (!$oProperty->isPrivate()) {
				$oValue = $oProperty->getValue($this);
			} else {
				$oProperty->setAccessible(true);
				$oValue = $oProperty->getValue($this);
				//				$oProperty->setAccessible(false);

				//				$oValue = $this->__get($oProperty->getName());
			}
			if ( FieldA::isValid($oValue)) {
				$aRet[$oProperty->getName()] = $oValue;
			}
		}

		return $aRet;
	}

	/**
	 * gets all the column names as an array
	 * @return string[]
	 */
	public function getFieldNames ($bWithAlias = false) {
		$aFields = $this->getFields();
		if ($bWithAlias === false) {
			return array_keys ($aFields);
		} else {
			$aRet = array();
		/* @var FieldA $oField */
			foreach ($aFields as $oField) {
				if ($oField->getAlias()) {
					$aRet[] = $oField->getAlias();
				} else {
					$aRet[] = $oField->getTableAlias() . '.' . $oField->getName();
				}

			}
			return $aRet;
		}
	}

	public function addIndex ( IndexA $oIndex) {
		$this->aIndexes[] = $oIndex;
	}

	public function getIndexes ($bWithPrimaryKey = false) {
		$aIndexes = array ();
		$oPk = $this->getPrimaryKey();
		if ($bWithPrimaryKey && KeyPrimary::isValid($oPk)) {
			$aIndexes[] = $oPk;
		}

		$aIndexes = array_merge ($aIndexes, $this->aIndexes);
		return $aIndexes;
	}

	/**
	 * returns an array of key=>value for all properties of the current object
	 * @return mixed[]
	 */
	public function toArray () {
		$aRet = array();

		foreach ($this->getFields() as $sFieldName => $oField) {
			$aRet[$sFieldName]	= $oField->getValue();
		}

		return $aRet;
	}

	/**
	 * Receives an array of keys=> values and constructs an entity based on
	 * the existing ones.
	 * Returns:
	 * 1 if all array keys existed as properties of the object
	 * 0 if one of the keys didn't exist as a property of the object
	 * 2 if there were properties which didn't have a corresponding key=>value pair
	 * @param mixed[string] $aIncArray
	 * @return int
	 * @TODO : this doesn't work for columns named differently from the field property of the domain object FIXIT
	 */
	public function fromArray ($aIncArray) {
		if (!is_array($aIncArray)) {
			return -1;
		}
		$iStatus = 1;
		foreach ($aIncArray as $sFieldName => $mValue) {
			if (($sJustFieldName =  stristr($sFieldName, ':'))) {
				// removing the alias of the table from the array's index
				$sFieldName = substr($sJustFieldName, 1);
			}

			if ($this->valid ($sFieldName)) {
				$this->$sFieldName->setValue ($mValue);
			} else {
				$iStatus = 0;
			}
		}
		return $iStatus;
	}

	static public function isValid($oIncObject) {
		return ($oIncObject instanceof static);
	}

	public function reset() {
		foreach ($this->getFieldNames() as $sFieldName) {
			$this->$sFieldName->setValue(null);
		}
	}
}
