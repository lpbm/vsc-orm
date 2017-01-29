<?php
/**
 * @pacakge \orm\domain\access\clauses
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.01
 */
namespace orm\domain\access\clauses;

use orm\domain\access\drivers\SqlGenericDriver;
use orm\domain\connections\ConnectionA;
use orm\domain\domain\clauses\Clause;
use orm\domain\domain\fields\FieldA;
use vsc\infrastructure\Object;

class SqlClauseAccess extends Object {
	private $oConnection;
	private $oGrammarHelper;

	/**
	 * @param ConnectionA $oConnection
	 * @return void
	 */
	public function setConnection ($oConnection) {
		$this->oConnection = $oConnection;
	}

	/**
	 * @return ConnectionA
	 */
	public function getConnection () {
		return $this->oConnection;
	}

	/**
	 * @param SqlGenericDriver $oGrammarHelper
	 * @return void
	 */
	public function setGrammarHelper ($oGrammarHelper) {
		$this->oGrammarHelper = $oGrammarHelper;
	}

	/**
	 * @return SqlGenericDriver
	 */
	public function getGrammarHelper () {
		return $this->oGrammarHelper;
	}

	/**
	 * @param Clause $oClause
	 * @return string
	 */
	public function getDefinition ( Clause $oClause) {
		$verb = null;
		if (is_string($oClause->getSubject())) {
			return $oClause->getSubject();
		} elseif (Clause::isValid($oClause->getSubject())) {
			$subStr = $this->getDefinition($oClause->getSubject ());
		} elseif ( FieldA::isValid($oClause->getSubject())) {
			$subStr = ($oClause->getSubject()->getTableAlias() ?
				$this->getGrammarHelper()->FIELD_OPEN_QUOTE . $oClause->getSubject()->getTableAlias() . $this->getGrammarHelper()->FIELD_CLOSE_QUOTE . '.': '') .
				$this->getGrammarHelper()->FIELD_OPEN_QUOTE . $oClause->getSubject()->getName() . $this->getGrammarHelper()->FIELD_CLOSE_QUOTE;
		} else {
			return '';
		}

		if (is_null($oClause->getPredicative())) {
			if ($oClause->validPredicate ($oClause->getPredicate())) {
				$preStr = 'NULL';
			} else {
				$preStr = '';
			}
		} elseif (is_numeric($oClause->getPredicative ())) {
			$preStr = $oClause->getPredicative ();
		} elseif (is_string($oClause->getPredicative ())) {
			$preStr = $this->getConnection()->escape($oClause->getPredicative ());

			if (strtolower($oClause->getPredicate()) == 'like') {
				$preStr = '%'.$preStr.'%';
			}

			//$preStr = (stripos($preStr, '"') !== 0 ? '"'.$preStr.'"' : $preStr);//'"'.$preStr.'"';
		} elseif (is_array($oClause->getPredicative ())) {
			//FIXME: escape elements of the predicative array
			$preStr =  '("'.implode('", "',$oClause->getPredicative ()).'")';
		} elseif (FieldA::isValid($oClause->getPredicative ())) {
			$preStr = ($oClause->getPredicative()->getTableAlias() != 't' ? $this->getGrammarHelper()->FIELD_OPEN_QUOTE . $oClause->getPredicative()->getTableAlias() . $this->getGrammarHelper()->FIELD_CLOSE_QUOTE .'.': '').
					$this->getGrammarHelper()->FIELD_OPEN_QUOTE . $oClause->getPredicative()->getName(). $this->getGrammarHelper()->FIELD_CLOSE_QUOTE;
		} elseif ($oClause->getPredicative () instanceof Clause) {
			$subStr = $subStr;
			$preStr = $oClause->getPredicative ();
		}

		$retStr = $subStr.' ' . strtoupper($oClause->getPredicate()) . ' ' . $preStr;
		if (Clause::isValid($oClause->getSubject ()) && (Clause::isValid($oClause->getPredicative ())))
			return '('.$retStr.')';

		return $retStr;
	}
}
