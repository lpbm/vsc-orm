<?php
/**
 *
 * The way I'm doing this is stupid
 * @pacakge  \orm\domain\access
 * @author marius orcsik <marius@habarnam.ro>
 */
namespace orm\domain\access;

use orm\domain\access\clauses\SqlClauseAccess;
use orm\domain\access\drivers\MySqlDriver;
use orm\domain\access\drivers\PostgreSqlDriver;
use orm\domain\access\fields\FieldDateTimeAccess;
use orm\domain\access\fields\FieldDecimalAccess;
use orm\domain\access\fields\FieldEnumAccess;
use orm\domain\access\fields\FieldIntegerAccess;
use orm\domain\access\fields\FieldTextAccess;
use orm\domain\access\indexes\KeyFullTextAccess;
use orm\domain\access\indexes\KeyIndexAccess;
use orm\domain\access\indexes\KeyPrimaryAccess;
use orm\domain\access\indexes\KeyUniqueAccess;
use orm\domain\access\joins\JoinInnerAccess;
use orm\domain\access\joins\JoinOuterAccess;
use orm\domain\access\joins\JoinType;
use orm\domain\domain\indexes\IndexType;
use orm\domain\domain\joins\JoinA;
use orm\domain\connections\ConnectionA;
use orm\domain\connections\ConnectionType;
use orm\domain\domain\fields\FieldA;
use orm\domain\domain\fields\FieldType;
use orm\domain\domain\indexes\IndexA;
use vsc\infrastructure\Base;

class AccessFactory extends Base {
	 private $oConnection;

	 public function setConnection ( ConnectionA $oConnection) {
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

	 public function getGrammarHelper ( ConnectionA  $oConnection) {
	 	switch ($oConnection->getType()) {
	 		case ConnectionType::mysql:
	 			return new MySqlDriver();
	 			break;
	 		case ConnectionType::postgresql:
	 			return new PostgreSqlDriver();
	 			break;
	 		case ConnectionType::sqlite:
	 		case ConnectionType::mssql:
	 		case ConnectionType::nullsql:
	 			break;

	 	}
	 }

	 public function getField ( FieldA $oField) {
	 	$oConnection = $this->getConnection();
		switch ($oField->getType()) {
			case ( FieldType::INTEGER):
			case ('integer'):
				if (!($this->oFieldInteger instanceof FieldIntegerAccess)) {
					$this->oFieldInteger = new FieldIntegerAccess();
					$this->oFieldInteger->setConnection($oConnection);
					$this->oFieldInteger->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldInteger;
				break;
			case ( FieldType::TEXT):
			case ('varchar'):
			case ('text'):
				if (!($this->oFieldText instanceof FieldTextAccess)) {
					$this->oFieldText = new FieldTextAccess();
					$this->oFieldText->setConnection($oConnection);
					$this->oFieldText->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldText;
				break;
			case ( FieldType::DATETIME):
			case ('datetime'):
				if (!($this->oFieldDate instanceof FieldDateTimeAccess)) {
					$this->oFieldDate = new FieldDateTimeAccess();
					$this->oFieldDate->setConnection($oConnection);
					$this->oFieldDate->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldDate;
				break;
			case ( FieldType::ENUM):
			case ('enum'):
				if (!($this->oFieldEnum instanceof FieldEnumAccess)) {
					$this->oFieldEnum = new FieldEnumAccess();
					$this->oFieldEnum->setConnection($oConnection);
					$this->oFieldEnum->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldEnum;
				break;
			case ( FieldType::DECIMAL):
				if (!($this->oFieldEnum instanceof FieldDecimalAccess)) {
					$this->oFieldDecimal = new FieldDecimalAccess();
					$this->oFieldDecimal->setConnection($oConnection);
					$this->oFieldDecimal->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oFieldDecimal;
				break;
		}

		return $oFieldAccess;
	}

	public function getIndex ( IndexA $oIndex) {
		$oIndexAccess = null;
		$oConnection = $this->getConnection();
		switch ($oIndex->getType()) {
			case ( IndexType::PRIMARY):
				if (!($this->oKeyPrimary instanceof KeyPrimaryAccess)) {
					$this->oKeyPrimary = new KeyPrimaryAccess();
					$this->oKeyPrimary->setConnection($oConnection);
					$this->oKeyPrimary->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyPrimary;
				break;
			case ( IndexType::FULLTEXT):
				if (!($this->oKeyFullText instanceof KeyFullTextAccess)) {
					$this->oKeyFullText = new KeyFullTextAccess();
					$this->oKeyFullText->setConnection($oConnection);
					$this->oKeyFullText->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyFullText;
				break;
			case ( IndexType::INDEX):
				if (!($this->oKeyIndex instanceof KeyIndexAccess)) {
					$this->oKeyIndex = new KeyIndexAccess();
					$this->oKeyIndex->setConnection($oConnection);
					$this->oKeyIndex->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyIndex;
				break;
			case ( IndexType::UNIQUE):
				if (!($this->oKeyUnique instanceof KeyUniqueAccess)) {
					$this->oKeyUnique = new KeyUniqueAccess();
					$this->oKeyUnique->setConnection($oConnection);
					$this->oKeyUnique->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oKeyUnique;
				break;
		}
	}

	public function getClause () {
		$oConnection = $this->getConnection();
		if (!($this->oClause instanceof SqlClauseAccess)) {
			$oConnection = $this->getConnection();
			$this->oClause = new SqlClauseAccess();
			$this->oClause->setConnection ($oConnection);
			$this->oClause->setGrammarHelper ($this->getGrammarHelper($oConnection));
		}

		return $this->oClause;
	}

	public function getJoin ( JoinA $oJoin) {
		$oConnection = $this->getConnection();
		switch ($oJoin->getType()) {
			case ( JoinType::INNER):
				if (!($this->oJoinInner instanceof JoinInnerAccess)) {
					$this->oJoinInner = new JoinInnerAccess();
					$this->oJoinInner->setConnection($oConnection);
					$this->oJoinInner->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oJoinInner;
				break;
			case ( JoinType::OUTER):
			case ( JoinType::LEFT):
			case ( JoinType::RIGHT):
				if (!($this->oJoinOuter instanceof JoinOuterAccess)) {
					$this->oJoinOuter = new JoinOuterAccess();
					$this->oJoinOuter->setConnection($oConnection);
					$this->oJoinOuter->setGrammarHelper($this->getGrammarHelper($oConnection));
				}
				return $this->oJoinOuter;
				break;
		}
	}
}
