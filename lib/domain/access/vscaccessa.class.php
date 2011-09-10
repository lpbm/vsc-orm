<?php
import ('domain/connections');
import ('domain/access/drivers');
abstract class vscAccessA extends vscObject {
	/**
	 * @var vscConnectionA
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
				case vscConnectionType::mysql :
					return new mySQLDriver();

			}
			$this->setGrammarHelper($oGrammarHelper);
		}
		return $this->oGrammarHelper;
	}

	public function setConnection (vscConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}

	/**
	 * @return vscConnectionA
	 */
	public function getConnection () {
		if (!vscConnectionA::isValid($this->oConnection)) {
			$this->setConnection(vscConnectionFactory::connect(
				$this->getDatabaseType(),
				$this->getDatabaseHost(),
				$this->getDatabaseUser(),
				$this->getDatabasePassword()
			));

			$this->getConnection()->selectDatabase($this->getDatabaseName());
		}

		if (!vscConnectionA::isValid($this->oConnection)) {
			throw new vscExceptionConnection('Could not connect to databse. Please check configuration.');
		}

		return $this->oConnection;
	}

	final public function __construct() {}

	abstract protected function getDatabaseType();

	abstract protected function getDatabaseHost();

	abstract protected function getDatabaseUser();

	abstract protected function getDatabasePassword();

	abstract protected function getDatabaseName();

	public function getTableName (vscDomainObjectI $oDomainObject, $bWithAlias = false) {
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

	private function getQuotedFieldList (vscDomainObjectI $oDomainObject, $bWithAlias = false, $bWithTableAlias = false) {
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

	public function getClausesString (vscDomainObjectI $oDomainObject) {
		$sStr = '';
		$aStrClauses = array();
		if (vscCompositeDomainObjectA::isValid($oDomainObject)) {
			/* @var $oDomainObject vscSQLModelA */
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


	private function getSelect (vscDomainObjectI $oDomainObject) {
		$o = $this->getGrammarHelper();

		$aSelects = $aNames = array ();

		if (vscCompositeDomainObjectA::isValid($oDomainObject)) {
			$aParameters = $oDomainObject->getDomainObjects();
		} elseif (vscDomainObjectA::isValid($oDomainObject)) {
			$aParameters[$oDomainObject->getName()] = $oDomainObject;
		}

		d (vscDomainObjectA::isValid($oDomainObject),vscCompositeDomainObjectA::isValid($oDomainObject));
		foreach ($aParameters as $key => $oParameter) {
			if (!vscDomainObjectA::isValid($oParameter)) {
				unset ($aParameters[$key]);
				continue;
			}
			/* @var $oParameter vscDomainObjectA */
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
	 * @param vscDomainObjectI $oDomainObject
	 * @return string
	 */
	public function buildQuery ($iType, vscDomainObjectI $oDomainObject) {
		switch ($iType) {
			case vscQueryType::SELECT:
				return $this->getSelect($oDomainObject);
			break;
		}
	}

	public function loadByUnique (vscDomainObjectI $oDomainObject) {}

	public function loadByFilter (vscDomainObjectI $oDomainObject) {}

	public function loadFirstMatch (vscDomainObjectI $oDomainObject) {}

	public function loadAllMatching (vscDomainObjectI $oDomainObject) {}
}