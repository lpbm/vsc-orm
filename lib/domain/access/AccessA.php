<?php
use vsc\infrastructure\Object;

abstract class AccessA extends Object {
	/**
	 * @var ConnectionA
	 */
	private $oConnection;
	/**
	 * @var SQLGenericDriver
	 */
	private $oGrammarHelper;

	public function setGrammarHelper (SQLGenericDriver $oGrammarHelper) {
		$this->oGrammarHelper = $oGrammarHelper;
	}

	/**
	 * @return SQLGenericDriver
	 */
	public function getGrammarHelper () {
		if (!sqlDriverA::isValid($this->oGrammarHelper)) {
			switch ($this->getDatabaseType()) {
				case ConnectionType::mysql :
					return new mySQLDriver();

			}
			$this->setGrammarHelper($oGrammarHelper);
		}
		return $this->oGrammarHelper;
	}

	public function setConnection ( ConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}

	/**
	 * @return ConnectionA
	 */
	public function getConnection () {
		if (!ConnectionA::isValid($this->oConnection)) {
			$this->setConnection( ConnectionFactory::connect(
				$this->getDatabaseType(),
				$this->getDatabaseHost(),
				$this->getDatabaseUser(),
				$this->getDatabasePassword()
			));

			$this->getConnection()->selectDatabase($this->getDatabaseName());
		}

		if (!ConnectionA::isValid($this->oConnection)) {
			throw new ExceptionConnection('Could not connect to databse. Please check configuration.');
		}

		return $this->oConnection;
	}

	final public function __construct() {}

	abstract protected function getDatabaseType();

	abstract protected function getDatabaseHost();

	abstract protected function getDatabaseUser();

	abstract protected function getDatabasePassword();

	abstract protected function getDatabaseName();

	public function getTableName ( DomainObjectI $oDomainObject, $bWithAlias = false) {
//		$o = $this->getConnection();
//
//		$sRet = $o->FIELD_OPEN_QUOTE . $oDomainObject->getTableName() . $o->FIELD_CLOSE_QUOTE;
//		if ($bWithAlias && $oDomainObject->hasTableAlias()) {
//			$sRet .=  $o->_AS($o->FIELD_OPEN_QUOTE . $oDomainObject->getTableAlias() . $o->FIELD_CLOSE_QUOTE);
//		}
//
//		return $sRet;
	}
	public function getGroupByString () {
//		$sGroupBy = '';
//		if (count ($this->aGroupBys) > 0 ) {
//			foreach ($this->aGroupBys as $oField) {
//				$oDomainObject = $oField->getParent();
//				$sGroupBy .= ($oField->hasAlias() ? $oField->getAlias() : $oField->getName());
//			}
//			return $this->getConnection()->_GROUP($sGroupBy);
//		} else {
//			return '';
//		}
		}

	private function getQuotedFieldList ( DomainObjectI $oDomainObject, $bWithAlias = false, $bWithTableAlias = false) {
		$aRet = array ();
		$o = $this->getGrammarHelper();

		foreach ($oDomainObject->getFields() as $oField) {
			$sField = '';
			if ($bWithTableAlias && $oDomainObject->getTableAlias()) {
				$sField = $o->FIELD_OPEN_QUOTE .  $oDomainObject->getTableAlias() . $o->FIELD_CLOSE_QUOTE . '.';
			}

			$sField .= $o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE . ($bWithAlias && $oField->getAlias() ? $o->_AS() . $o->FIELD_OPEN_QUOTE . $oField->getAlias() . $o->FIELD_CLOSE_QUOTE : '');

			$aRet[] = $sField;
		}

		return $aRet;
	}

	public function getClausesString ( DomainObjectI $oDomainObject) {
		$sStr = '';
		$aStrClauses = array();
		if ( CompositeDomainObjectA::isValid($oDomainObject)) {
			/* @var SQLModelA $oDomainObject */
			$aClauses = $oDomainObject->getClauses();
		}
		if ($aClauses > 0 ) {
			foreach ($aClauses as $oClause) {
				$aStrClauses[] .= $this->getAccess()->getDefinition($oClause);
			}

			$sStr = implode ($this->getDriver()->_AND(), $aStrClauses);
		}

		return $sStr;
	}


	private function getSelect ( DomainObjectI $oDomainObject) {
		$o = $this->getGrammarHelper();

		$aSelects = $aNames = array ();

		if ( CompositeDomainObjectA::isValid($oDomainObject)) {
			$aParameters = $oDomainObject->getDomainObjects();
		} elseif ( DomainObjectA::isValid($oDomainObject)) {
			$aParameters[$oDomainObject->getName()] = $oDomainObject;
		}

		d ( DomainObjectA::isValid($oDomainObject), CompositeDomainObjectA::isValid($oDomainObject));
		foreach ($aParameters as $key => $oParameter) {
			if (!DomainObjectA::isValid($oParameter)) {
				unset ($aParameters[$key]);
				continue;
			}
			/* @var DomainObjectA $oParameter */
			$oParameter->setTableAlias('t'.$key);
			$aSelects[] =  $this->getFieldsForSelect($oParameter, true);

			$aNames[] = $this->getTableName($oParameter, true);
			$this->buildDefaultClauses($oParameter);
		}

		$aWheres = array();

		$sRet = $o->_SELECT (implode (', ', $aSelects)) .
			$o->_FROM(implode (', ', $aNames)) ."\n" .
		d ($sRet);
		//$this->getJoinsString() .
		$o->_WHERE($this->getClausesString ()) .
		$this->getGroupByString() .
		$this->getOrderByString() .
		$this->getLimitString();

		return $sRet . ';';

		return $sSql . ';';
	}

	/**
	 *
	 * @param int $iType
	 * @param DomainObjectI $oDomainObject
	 * @return string
	 */
	public function buildQuery ($iType, DomainObjectI $oDomainObject) {
		switch ($iType) {
			case QueryType::SELECT:
				return $this->getSelect($oDomainObject);
			break;
		}
	}

	public function loadByUnique ( DomainObjectI $oDomainObject) {}

	public function loadByFilter ( DomainObjectI $oDomainObject) {}

	public function loadFirstMatch ( DomainObjectI $oDomainObject) {}

	public function loadAllMatching ( DomainObjectI $oDomainObject) {}
}
