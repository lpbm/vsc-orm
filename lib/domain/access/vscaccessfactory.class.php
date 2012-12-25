<?php
/**
 *
 * The way I'm doing this is stupid
 * @package vsc_domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 */

import ('access/drivers');
import ('access/fields');
class vscAccessFactory extends vscObject {
	 private $oConnection;

	 public function setConnection (vscConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}

	public function getConnection () {
		return $this->oConnection;
	}

	 private $oFieldInteger;
	 private $oFieldText;
	 private $oFieldDate;
	 private $oFieldDecimal;
	 private $oFieldEnum;

	 private $oClause;

	 private $oKeyFullText;
	 private $oKeyIndex;
	 private $oKeyPrimary;
	 private $oKeyUnique;

	 private $oJoinInner;
	 private $oJoinOuter;

	 public function getGrammarHelper (vscConnectionA  $oConnection) {
	 	switch ($oConnection->getType()) {
	 		case vscConnectionType::mysql:
	 			return new mySQLDriver();
	 			break;
	 		case vscConnectionType::postgresql:
	 			return new postgreSQLDriver();
	 			break;
	 		case vscConnectionType::sqlite:
	 		case vscConnectionType::mssql:
	 		case vscConnectionType::nullsql:
	 			break;

	 	}
	 }

	 public function getField (vscFieldA $oField) {
	 	$oConnection = $this->getConnection();
		switch ($oField->getType()) {
			case (vscFieldType::INTEGER):
			case ('integer'):
				if (!($this->oFieldInteger instanceof vscFieldIntegerAccess)) {
					$this->oFieldInteger = new vscFieldIntegerAccess();
					$this->oFieldInteger->setConnection($oConnection);
					$this->oFieldInteger->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldInteger;
				break;
			case (vscFieldType::TEXT):
			case ('varchar'):
			case ('text'):
				if (!($this->oFieldText instanceof vscFieldTextAccess)) {
					$this->oFieldText = new vscFieldTextAccess();
					$this->oFieldText->setConnection($oConnection);
					$this->oFieldText->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldText;
				break;
			case (vscFieldType::DATETIME):
			case ('datetime'):
				if (!($this->oFieldDate instanceof vscFieldDateTimeAccess)) {
					$this->oFieldDate = new vscFieldDateTimeAccess();
					$this->oFieldDate->setConnection($oConnection);
					$this->oFieldDate->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldDate;
				break;
			case (vscFieldType::ENUM):
			case ('enum'):
				if (!($this->oFieldEnum instanceof vscFieldEnumAccess)) {
					$this->oFieldEnum = new vscFieldEnumAccess();
					$this->oFieldEnum->setConnection($oConnection);
					$this->oFieldEnum->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldEnum;
				break;
			case (vscFieldType::DECIMAL):
				if (!($this->oFieldEnum instanceof vscFieldDecimalAccess)) {
					$this->oFieldDecimal = new vscFieldDecimalAccess();
					$this->oFieldDecimal->setConnection($oConnection);
					$this->oFieldDecimal->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldDecimal;
				break;
		}

		return $oFieldAccess;
	}

	public function getIndex (vscIndexA $oIndex) {
		$oIndexAccess = null;
		$oConnection = $this->getConnection();
		switch ($oIndex->getType()) {
			case (vscIndexType::PRIMARY):
				if (!($this->oKeyPrimary instanceof vscKeyPrimaryAccess)) {
					$this->oKeyPrimary = new vscKeyPrimaryAccess();
					$this->oKeyPrimary->setConnection($oConnection);
					$this->oKeyPrimary->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyPrimary;
				break;
			case (vscIndexType::FULLTEXT):
				if (!($this->oKeyFullText instanceof vscKeyFullTextAccess)) {
					$this->oKeyFullText = new vscKeyFullTextAccess();
					$this->oKeyFullText->setConnection($oConnection);
					$this->oKeyFullText->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyFullText;
				break;
			case (vscIndexType::INDEX):
				if (!($this->oKeyIndex instanceof vscKeyIndexAccess)) {
					$this->oKeyIndex = new vscKeyIndexAccess();
					$this->oKeyIndex->setConnection($oConnection);
					$this->oKeyIndex->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyIndex;
				break;
			case (vscIndexType::UNIQUE):
				if (!($this->oKeyUnique instanceof vscKeyUniqueAccess)) {
					$this->oKeyUnique = new vscKeyUniqueAccess();
					$this->oKeyUnique->setConnection($oConnection);
					$this->oKeyUnique->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyUnique;
				break;
		}
	}

	public function getClause () {
		$oConnection = $this->getConnection();
		if (!($this->oClause instanceof vscSqlClauseAccess)) {
			$oConnection = $this->getConnection();
			$this->oClause = new vscSqlClauseAccess();
			$this->oClause->setConnection ($oConnection);
			$this->oClause->setGrammarHelper ($this->getGrammarHelper($oConnection));
		}

		return $this->oClause;
	}

	public function getJoin (vscJoinA $oJoin) {
		$oConnection = $this->getConnection();
		switch ($oJoin->getType()) {
			case (vscJoinType::INNER):
				if (!($this->oJoinInner instanceof vscJoinInnerAccess)) {
					$this->oJoinInner = new vscJoinInnerAccess();
					$this->oJoinInner->setConnection($oConnection);
					$this->oJoinInner->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oJoinInner;
				break;
			case (vscJoinType::OUTER):
			case (vscJoinType::LEFT):
			case (vscJoinType::RIGHT):
				if (!($this->oJoinOuter instanceof vscJoinOuterAccess)) {
					$this->oJoinOuter = new vscJoinOuterAccess();
					$this->oJoinOuter->setConnection($oConnection);
					$this->oJoinOuter->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oJoinOuter;
				break;
		}
	}
}