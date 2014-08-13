<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.04.01
 */
namespace orm\domain\access\tables;

use orm\domain\access\AccessFactory;
use orm\domain\domain\clauses\Clause;
use orm\domain\domain\indexes\IndexType;
use orm\domain\domain\indexes\KeyUnique;
use orm\domain\domain\joins\JoinA;
use orm\domain\domain\joins\Join;
use orm\domain\domain\joins\JoinOuter;
use orm\domain\models\CompositeSqlModelA;
use orm\domain\domain\DomainObjectI;
use orm\domain\domain\DomainObjectA;
use orm\domain\domain\fields\FieldA;
use orm\domain\domain\indexes\IndexA;
use orm\domain\domain\indexes\KeyPrimary;
use orm\domain\domain\fields\FieldInteger;
use orm\domain\ExceptionConstraint;
use orm\domain\domain\ExceptionDomain;
use orm\domain\domain\fields\FieldI;

use vsc\domain\access\ExceptionAccess;
use vsc\ExceptionError;

class SqlAccess extends SqlAccessA {
	private $aGroupBys	= array();
	private $aOrderBys	= array();
	private $aClauses	= array();
	private $aJoins		= array();
	private $iStart;
	private $iCount;
	private $aFieldAggregators = array();

	private $oFactory;

	final public function __construct() {
		parent::__construct();

		$this->oFactory = new AccessFactory();
		$this->oFactory->setConnection($this->getConnection());
	}

	static protected function getDomainObjects ($aParameters) {
		if (is_array($aParameters) && count($aParameters) == 1 && is_array($aParameters[0])) {
			$aParameters = $aParameters[0];
		}

		$aDomainObjects = array();
		foreach ($aParameters as $key => $oParameter) {
			if ( CompositeSqlModelA::isValid($oParameter)) {
				unset ($aParameters[$key]);
				/* @var CompositeSqlModelA $oParameter */
				$aDomainObjects = array_merge ($aDomainObjects, $oParameter->getDomainObjects());
			}
		}

		if (count ($aDomainObjects) < 1) {
			$aDomainObjects = $aParameters;
		}

		return $aDomainObjects;
	}

	public function getDatabaseType() {
		throw new ExceptionDomain('Please implement ['. __METHOD__ . '] in a child class.');
	}
	public function getDatabaseHost() {
		throw new ExceptionDomain('Please implement ['. __METHOD__ . '] in a child class.');
	}
	public function getDatabaseUser() {
		throw new ExceptionDomain('Please implement ['. __METHOD__ . '] in a child class.');
	}
	public function getDatabasePassword() {
		throw new ExceptionDomain('Please implement ['. __METHOD__ . '] in a child class.');
	}
	public function getDatabaseName() {
		throw new ExceptionDomain('Please implement ['. __METHOD__ . '] in a child class.');
	}


	public function getAccess($oObject = null) {
		if ( JoinA::isValid($oObject)) {
			return $this->oFactory->getJoin($oObject);
		}

		if ( FieldA::isValid($oObject)) {
			return $this->oFactory->getField($oObject);
		}

		if ( IndexA::isValid($oObject)) {
			return $this->oFactory->getIndex($oObject);
		}

		if (is_null($oObject) || Clause::isValid($oObject)) {
			return $this->oFactory->getClause($oObject);
		}
	}

	public function getQuotedFieldList ( DomainObjectA $oDomainObject, $bWithAlias = false, $bWithTableAlias = false) {
		$aRet = array ();
		$o = $this->getDriver();

		foreach ($oDomainObject->getFields() as $oField) {
			$sField = '';
			if ($bWithTableAlias && $oDomainObject->getTableAlias()) {
				$sField = $o->FIELD_OPEN_QUOTE .  $oDomainObject->getTableAlias() . $o->FIELD_CLOSE_QUOTE . '.';
			}

			$sField .= $o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE . ($bWithAlias && $oField->getAlias() ? $o->_AS($oField->getAlias()): '');

			$aRet[] = $sField;
		}

		return $aRet;
	}

	public function getFieldValues ( DomainObjectI $oDomainObject) {
		$aRet = array ();
		foreach ($oDomainObject->getFields() as $oField) {
			try {
				$aRet[$oField->getName()] = $this->getAccess($oField)->escapeValue($oField);
			} catch ( ExceptionConstraint $e) {
				//
			} catch ( ExceptionError $e) {
				\vsc\d($e, $oField, $this->getAccess($oField));
			}
		}
		return $aRet;
	}

	/**
	 *
	 * @param DomainObjectI $oDomainObject
	 * @param array $aValues array (0 => array (// usable with fromArray), ... )
	 * @return string
	 */
	public function outputInsertSql ( DomainObjectI $oDomainObject, $aValuesGroup = array()) {
		$sSql = '';
		$aWheres = array();
		$aUpdateFields = array();

		$o = $this->getDriver();
		$sSql = $o->_INSERT($o->FIELD_OPEN_QUOTE . $oDomainObject->getTableName() . $o->FIELD_CLOSE_QUOTE);

		$aFields = $oDomainObject->toArray();
		$aValues = array_keys ($aFields);

		$sSql .= ' ( '.implode (', ', $this->getQuotedFieldList ($oDomainObject)) . ' )';

		foreach ($oDomainObject->getFields() as $oField) {
			if ($oField->hasValue()) {
				$aInsertFields[] = $o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE . ' = ' . $this->getQuotedValue($oField->getValue());
			}
		}

		$aValueArray = array();
		if (count($aValuesGroup) > 0) {
			$aInitialValues = $oDomainObject->toArray();
			foreach ($aValuesGroup as $aValues) {
				$oDomainObject->reset();
				$oDomainObject->fromArray ($aValues);
				$aValueArray[] = implode (', ',  $this->getFieldValues($oDomainObject));
			}
			$sValueString = '( '. implode (' ), ( ', $aValueArray) . ' )';
			$oDomainObject->fromArray ($aInitialValues);
		} else {
			$sValueString = '( ' . implode (', ', $this->getFieldValues($oDomainObject)) . ' )';
		}

		$sSql .= $o->_VALUES($sValueString);

		return $sSql . ';';
	}

	/**
	 * this is not exactly perfect, as it assumes the domain object has a primary key
	 * @param DomainObjectI $oDomainObject
	 * @return string
	 */
	public function outputUpdateSql ( DomainObjectI $oDomainObject) {
		$sSql = '';
		$aWheres = array();
		$aUpdateFields = array();

		$o = $this->getDriver();
		$sSql = $o->_UPDATE($o->FIELD_OPEN_QUOTE . $oDomainObject->getTableName() . $o->FIELD_CLOSE_QUOTE) . $o->_SET();

		$oPk = $oDomainObject->getPrimaryKey();

		/* @var FieldA $oField */
		foreach ($oDomainObject->getFields() as $oField) {
			if (!$oPk->hasField ($oField) && $oField->hasValue()) {
				$aUpdateFields[] = $o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE . ' = ' . $this->getQuotedValue($oField->getValue());
			}
		}

		$sSql .= implode (', ', $aUpdateFields);

		foreach ($oPk->getFields() as $oField) {
			$aWheres[] = $o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE . ' = ' . $this->getQuotedValue($oField->getValue());
		}
		$sSql .= $o->_WHERE(implode ($o->_AND(), $aWheres));

		return $sSql . ';';
	}


	/**
	 * @TODO - next item on the agenda
	 * @param DomainObjectA $oDomainObject
	 * @param DomainObjectA ...
	 * @return string
	 */
	public function outputSelectSql () {
		$aParameters = func_get_args();
		$aSelects = $aNames = array ();

		$aDomainObjects = static::getDomainObjects($aParameters);

		$cnt = count($this->aJoins);
		foreach ($aDomainObjects as $key => $oParameter) {
			if ( DomainObjectA::isValid($oParameter)) {
				/* @var DomainObjectA $oParameter */
				if (!$oParameter->hasTableAlias()) {
					$oParameter->setTableAlias('t' . $cnt++);
				}
				$aSelects[] =  $this->getFieldsForSelect($oParameter, true);

				if (!$this->tableIsJoined($oParameter) || $oParameter->getTableAlias() == 't0') {
					$aNames[] = $this->getTableName($oParameter, true);
				}
				$this->buildDefaultClauses($oParameter);
			}
		}

		$aWheres = array();

		$o = $this->getDriver();

		$sRet = $o->_SELECT (implode (',' . "\n\t", $aSelects)) . "\n" .
		$o->_FROM(implode (', ', $aNames)) ."\n" .
		$this->getJoinsString() .
		$o->_WHERE($this->getClausesString ()) .
		$this->getGroupByString() .
		$this->getOrderByString() .
		$this->getLimitString();

		return $sRet . ';';
	}


	public function outputDeleteSql ( DomainObjectI $oDomainObject) {
		$sSql = '';
		$aWheres = array();

		$o = $this->getDriver();
		$sSql = $o->_DELETE($this->getTableName($oDomainObject));

		$oPk = $oDomainObject->getPrimaryKey();

		foreach ($oPk->getFields() as $oField) {
			$aWheres[] = ($oDomainObject->hasTableAlias() ? $o->FIELD_OPEN_QUOTE . $oDomainObject->getTableAlias() . $o->FIELD_CLOSE_QUOTE . '.' : '') .
			$o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE . ' = ' . $this->getQuotedValue($oField->getValue());
		}
		$sSql .= $o->_WHERE(implode ($o->_AND(), $aWheres));

		return $sSql . ';';
	}

	public function getTableName ( DomainObjectI $oDomainObject, $bWithAlias = false) {
		$o = $this->getDriver();

		$sRet = $o->FIELD_OPEN_QUOTE . $oDomainObject->getTableName() . $o->FIELD_CLOSE_QUOTE;
		if ($bWithAlias && $oDomainObject->hasTableAlias()) {
			$sRet .=  $o->_AS($o->FIELD_OPEN_QUOTE . $oDomainObject->getTableAlias() . $o->FIELD_CLOSE_QUOTE);
		}

		return $sRet;
	}

	public function setFieldAggregatorFunction ($sFunction, FieldI $oField) {
		$this->aFieldAggregators[$oField->getName()] = $sFunction;
	}

	public function getFieldAggregatorFunction( FieldI $oField) {
		return $this->aFieldAggregators[$oField->getName()];
	}

	public function hasFieldAggregatorFunction ( FieldI $oField) {
		return array_key_exists($oField->getName(), $this->aFieldAggregators);
	}

	public function getFieldsForSelect ( DomainObjectI $oDomainObject, $bWithAlias = false, $bAllFields = true) {
		$aSelectFields = array ();
		/* @var FieldA $oField */

		$o = $this->getDriver();

		foreach ($oDomainObject->getFields() as $oField) {
			if ($bAllFields || is_null($oField->getValue())) {
				$sFieldSelect = ($oDomainObject->hasTableAlias() ? $o->FIELD_OPEN_QUOTE . $oDomainObject->getTableAlias() . $o->FIELD_CLOSE_QUOTE . '.' : '') .
				$o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE;

				if ($this->hasFieldAggregatorFunction($oField)) {
					$sFieldSelect = sprintf($this->getFieldAggregatorFunction($oField), $sFieldSelect);
				}
				$aSelectFields[] = $sFieldSelect . ($bWithAlias && $oField->hasAlias() ? $o->_AS($o->FIELD_OPEN_QUOTE . $oField->getAlias(). $o->FIELD_CLOSE_QUOTE) : '');
			}
		}

		return implode(', ', $aSelectFields);
	}

	public function getQuotedValue ($mValue) {
		$o = $this->getDriver();
		$mValue		=  $this->getConnection()->escape($mValue);
		if (is_numeric($mValue) || is_null($mValue)) {
			$sCondition = $mValue;
		} elseif (is_string($mValue)) {
			// this should be moved to the sql driver
			$sCondition = $o->STRING_OPEN_QUOTE . $mValue . $o->STRING_CLOSE_QUOTE;
		}

		return $sCondition;
	}

	public function buildDefaultClauses ( DomainObjectI $oDomainObject) {
		$o = $this->getDriver();
		$aWheres = array();
		/* @var FieldA $oField */
		foreach ($oDomainObject->getFields() as $oField) {
			if ($oField->hasValue()) {
				// outputing only clauses not in a join
				$aWheres[]	= new Clause($oField, '=', $oField->getValue());
			}
		}

		if (count($this->aClauses) == 0 && count($aWheres) == 0) {
			$this->aClauses = array (new Clause ($o->TRUE));
		} else {
			$this->aClauses = array_merge($this->aClauses, $aWheres);
		}
	}

	/**
	 *
	 * @param DomainObjectA $oDomainObject
	 * @param DomainObjectA ...
	 * @param array $aFieldsArray
	 * @return array
	 */
	public function loadByFilter () { // this shold be moved to the composite model
		$aParameters = func_get_args();
		$aRet = array();
		$aTotalValues =  array();

		// this allows to call the self::outputSelectSql function with the parameters received
		// replaces the previous call : $this->outputSelectSql($aParameters); // where $aParameters was an array
		$aParameters = static::getDomainObjects($aParameters);

		$sSelect = $this->outputSelectSql($aParameters);
		$iNumRows = $this->getConnection()->query($sSelect);

		foreach ($aParameters as $oParameter) {
			$sLabel = $oParameter->getTableAlias() ? $oParameter->getTableAlias() : $oParameter->getTableName();
			$aTypes[$sLabel] = get_class($oParameter);
		}
		for ($i = 0; $i < $iNumRows; $i++) {
			$aAssoc = $this->getConnection()->getAssoc();
			foreach ($aAssoc as $sKey => $sValue) {
				$sTableAlias	= substr($sKey, 0, strpos($sKey, ':'));
				$sFieldName 	= substr($sKey, strpos($sKey, ':')+1);

				$aTotalValues[$sTableAlias][$i][$sFieldName] = $sValue;
			}
		}

		foreach ($aTotalValues as $sAlias => $aValuesArray) {
			$sType = $aTypes[$sAlias];
			foreach ($aValuesArray as $iKey => $aValues){
				$oDomainObject = new $sType();
				$oDomainObject->fromArray ($aValues);

				$aRet[$iKey][$sAlias] = $oDomainObject;
			}
		}

		return $aRet;
	}

	/**
	 *
	 * This has the only advantage over loadByFilter to ensure that the result returns a single entry
	 * @param array $aFieldsArray
	 * @throws ExceptionDomain
	 * @returns int
	 */
	public function getByUniqueIndex ( DomainObjectA $oDomainObject, $aFieldsArray = array()) {
		$bValid = false;
		$aFieldNames = array_keys($aFieldsArray);

		// tries to find a unique index of the entity which has values and selects an entry based on it
		// it will find at least the primary key
		$aIndexes = $oDomainObject->getIndexes(true);
		/* @var KeyUnique $oIndex */
		foreach ($aIndexes as $oIndex) {
			if (($oIndex->getType() & IndexType::UNIQUE) == IndexType::UNIQUE) {
				$aIndexFields 		= $oIndex->getFields();
				if (!empty ($aFieldNames)) {
					// we passed the values as the second parameter
					$aIndexFieldNames	= array_keys($aIndexFields);

					// setting the value of each field of the index
					if ($aIndexFieldNames == $aFieldNames) {
						foreach ($aIndexFields as $sFieldName => $oField) {
							$oField->setValue($aFieldsArray[$sFieldName]);
						}
						$bValid = true;
					}
				} else {
					// we check if the index fields have values
					foreach ($aIndexFields as $sFieldName => $oField) {
						if (!$oField->hasValue()) {
							break;
						}
						$bValid = true;
					}
				}
			}
		}

		if ($bValid) {
			$sSql = $this->outputSelectSql($oDomainObject);

			$this->getConnection()->query($sSql);
			return $oDomainObject->fromArray($this->getConnection()->getAssoc());
		} else {
			throw new ExceptionDomain('None of the object\'s unique indexes has all the neccessary values to get an instance.');
		}
	}

	/**
	 *
	 * This has the only advantage over loadByFilter to ensure that the result returns a single entry
	 * @param array $aFieldsArray
	 * @throws ExceptionDomain
	 * @returns int
	 */
	public function getByPrimaryKey ( DomainObjectA $oDomainObject, $aIndexValues = array()) {
		$bValid = false;
		if ($oDomainObject->hasPrimaryKey()) {
			$oPk = $oDomainObject->getPrimaryKey();
			$aIndexFields 		= $oPk->getFields();

			// setting the value of each field of the index if we have indexValues
			foreach ($aIndexFields as $sFieldName => $oField) {
				if (isset($aIndexValues[$sFieldName])) {
					$oField->setValue($aIndexValues[$sFieldName]);
					$bValid |= true;
				} elseif ($oField->hasValue()) {
					$bValid |= true;
				} else {
					$bValid &= false;
				}
			}
		}

		if ($bValid) {
			$sSql = $this->outputSelectSql($oDomainObject);

			$this->getConnection()->query($sSql);
			return $oDomainObject->fromArray($this->getConnection()->getAssoc());
		} else {
			throw new ExceptionDomain('None of the object\'s unique indexes has all the neccessary values to get an instance.');
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see lib/domain/access/SqlAccessI#save()
	 */
	public function save ( DomainObjectA $oDomainObject) {
		$bInsert = false;
		$oPk = $oDomainObject->getPrimaryKey();
		if ( KeyPrimary::isValid($oPk)) {
			foreach ($oPk->getFields() as $oField) {
				if ( FieldInteger::isValid($oField) && $oField->getAutoIncrement() == true) {
					$oAutoIncremeneted = $oField;
				}

				if (!$oField->hasValue()) {
					$bInsert = true;
					break;
				}
			}
		} else {
			$bInsert = true;
		}

		if ($bInsert) {
			$this->insert ($oDomainObject);
			// this is an ugly hack
			if (isset($oAutoIncremeneted) && FieldInteger::isValid($oAutoIncremeneted)) {
				$oAutoIncremeneted->setValue ($this->getConnection()->getLastInsertId());
			}
		} else {
			$this->update ($oDomainObject);
		}
	}

	public function where ($mSubject, $sPredicate= null, $mPredicative = null) {
		if (($mSubject instanceof Clause) && ($sPredicate == null || $mPredicative == null)) {
			$w = $mSubject;
		} elseif (!is_null ($mSubject)) {
			$w = new Clause($mSubject, $sPredicate, $mPredicative);
		} else {
			throw new ExceptionAccess ('Trying to add an empty where clause');
		}
		// this might generate an infinite recursion error on some PHP > 5.2 due to object comparison
		if (!in_array ($w, $this->aClauses, true)) {
			$this->aClauses[]	= $w;
		}
		return $this;
	}

	public function outerJoin ( FieldA $oLeftField, FieldA $oRightField) {
		$w = new JoinOuter($oLeftField, $oRightField);
		if (!in_array ($w, $this->aJoins/*, true*/)) {
			$this->aJoins[]	= $w;
		}
		//$this->where ($oLeftField, '=', $oRightField);
		return $this;
	}

	public function join ( FieldA $oLeftField, FieldA $oRightField = null, $iJoinType = null) {
		// @fixme : must validate fields??
		$w = new Join ($oLeftField, $oRightField, $iJoinType);
		if (!in_array ($w, $this->aClauses/*, true*/)) {
			$this->aClauses[]	= $w;
		}
		/* if (!in_array($oLeftField->getParent(), $this->aJoins)) {
			$sAlias = 't' . count ($this->aJoins);
			$oLeftField->getParent()->setTableAlias ($sAlias);
			$this->aJoins[$sAlias]	= $oLeftField->getParent();
		} */
		if (!is_null($oRightField) && !in_array($oRightField->getParent(), $this->aJoins)) {
			$sAlias = 't' . count ($this->aJoins);
			$oRightField->getParent()->setTableAlias ($sAlias);
			$this->aJoins[$sAlias]	= $oRightField->getParent();
		} elseif (!in_array($oLeftField->getParent(), $this->aJoins)) {
			$sAlias = 't' . count ($this->aJoins);
			$oLeftField->getParent()->setTableAlias ($sAlias);
			$this->aJoins[$sAlias]	= $oLeftField->getParent();
		}
		return $this;
	}

	public function getClausesString () {
		$sStr = '';
		$aStrClauses = array();
		if (count ($this->aClauses) > 0 ) {
			foreach ($this->aClauses as $oClause) {
				$aStrClauses[] .= $this->getAccess()->getDefinition($oClause);
			}

			$sStr = implode ($this->getDriver()->_AND(), $aStrClauses);
		}

		return $sStr;
	}

	public function setLimit ($iStart, $iCount = null) {
		if ($iCount === null) {
			$iCount = $iStart;
			$iStart = 0;
		}
		$this->iStart = $iStart;
		$this->iCount = $iCount;
	}

	public function getLimitString() {
		return $this->getDriver()->_LIMIT ($this->iStart, $this->iCount);
	}

	public function groupBy ( FieldA $oField) {
		if (!array_key_exists($oField->getName(), $this->aGroupBys)) {
			$this->aGroupBys[$oField->getName()] = $oField;
		}
		return $this;
	}

	public function orderBy ( FieldA $oField, $bAscending = true) {
		if ($bAscending) {
			$sDirection = ' ASC';
		} else {
			$sDirection = ' DESC';
		}
		if (!array_key_exists($oField->getName(), $this->aOrderBys)) {
			$this->aOrderBys[$oField->getName()] =  array (
					$oField,
					$sDirection
			);
		}
		return $this;
	}

	public function getGroupByString () {
		$sGroupBy = '';
		if (count ($this->aGroupBys) > 0 ) {
			foreach ($this->aGroupBys as $oField) {
				$oDomainObject = $oField->getParent();
				$sGroupBy .= ($oField->hasAlias() ? $oField->getAlias() : $oField->getName());
			}
			return $this->getDriver()->_GROUP($sGroupBy);
		} else {
			return '';
		}
	}

	public function getOrderByString () {
		$aOrderBys = '';
		if (count ($this->aOrderBys) > 0 ) {
			foreach ($this->aOrderBys as $aOrderBy) {
				$oField = $aOrderBy[0];
				$sDirection = $aOrderBy[1];
				$aOrderBys[] = ($oField->hasAlias() ? $oField->getAlias() : $oField->getName()) ;
			}

			return $this->getDriver()->_ORDER($aOrderBys, $sDirection);
		} else {
			return '';
		}
	}

	public function tableIsJoined ( DomainObjectA $oTable) {
		return isset($this->aJoins[$oTable->getTableAlias()]);
	}

	public function getJoinsString () {
		$sStr = '';
		$aStrJoins = array();
		$aTables = array () ;
		if (count ($this->aClauses) > 0 ) {
			/* @var Join $oJoin */
			foreach ($this->aClauses as $oJoin) {
				if ( Join::isValid($oJoin)) {
					$oJoinField = $oJoin->getRight();
					$oJoinOnField = $oJoin->getLeft();

					if (!FieldA::isValid($oJoinField)) {
						$oJoinField = $oJoin->getLeft();
					}
					$oJoinedTable = $oJoinField->getParent();
					if (!$this->tableIsJoined($oJoinedTable)) {
						$aTables [] = $oJoinedTable;
						$ajoins[] = $oJoin;
						$sAlias = $oJoinedTable->getTableAlias();
						if (!array_search($sAlias, $aStrJoins)) {
							$aStrJoins[$sAlias] = $this->getAccess($oJoin)->getDefinition($oJoin);
						}
					}
				}
			}

			$sStr = implode ("\t\n", $aStrJoins);
		}

		return $sStr;
	}

	/**
	 * Outputs the SQL necessary for creating the table
	 * @return string
	 */
//	public function outputCreateTableSQL ( DomainObjectI $oDomainObject) {
//		if ($this->getConnection()->getType() == ConnectionType::mysql){
//			$bFullText = false;
//		}
//
//		$sRet = $this->getConnection()->_CREATE ($oDomainObject->getTableName()) . "\n";
//		$sRet .= ' ( ' . "\n";
//
//		/* @var FieldA $oColumn */
//		foreach ($oDomainObject->getFields () as $oColumn) {
//			$sRet .= "\t" . $oColumn->getName() . ' ' . $this->getAccess($oColumn)->getDefinition($oColumn) ;
//			$sRet .= ', ' . "\n";
//		}
//
//		$aIndexes = $oDomainObject->getIndexes(true);
//		if (is_array ($aIndexes) && !empty($aIndexes)) {
//			foreach ($aIndexes as $oIndex) {
//				if ( IndexA::isValid($oIndex)) {
//					// checking for fulltext indexes
//					if ($this->getConnection()->getType() == ConnectionType::mysql && !$bFullText && KeyFullText::isValid($oIndex)){
//						$bFullText	= true;
//						$sEngine	= 'MyISAM';
//					} elseif ($this->getConnection()->getType() == ConnectionType::mysql) {
//						$sEngine	= $this->getConnection()->getEngine();
//					}
//					// this needs to be replaced with connection functionality : something like getConstraint (type, columns)
//					$sRet .=  "\t" . $this->getAccess($oIndex)->getDefinition($oIndex) . ", \n";
//				}
//			}
//		}
//
//		$sRet = substr( $sRet, 0, -3 );
//
//		$sRet.= "\n" . ' ) ';
//
//		if ($this->getConnection()->getType() == ConnectionType::mysql) {
//			$sRet.= ' ENGINE ' . $sEngine;
//		}
//
//		return $sRet . ';';
//	}
}
