<?php
/**
 * @package vsc_domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
abstract class vscSqlJoinAccessA extends vscAccessEntityA {

	public function getDefinition (vscJoinA $oJoin) {
		/* @var $o mySQLDriver */
		$o = $this->getGrammarHelper();

		$oRightField = $oJoin->getRight();
		$oLeftField = $oJoin->getLeft();
		if (vscFieldA::isValid($oRightField)) {
			$oJoinedTable = $oRightField->getParent();
			$oJoinOnTable = $oLeftField->getParent();
		} else {
			$oJoinedTable = $oJoin->getLeft()->getParent();
		}

		$sReturn = ' ' . $o->_JOIN($this->getType()) . $o->FIELD_OPEN_QUOTE . $oJoinedTable->getTableName() . $o->FIELD_CLOSE_QUOTE . $o->_AS ($o->FIELD_OPEN_QUOTE . $oJoinedTable->getTableAlias() . $o->FIELD_CLOSE_QUOTE);
		if (vscFieldA::isValid($oRightField)) {
			$sReturn .=  $o->_ON (
				$o->FIELD_OPEN_QUOTE . $oJoinOnTable->getTableAlias() . $o->FIELD_CLOSE_QUOTE . '.' . $o->FIELD_OPEN_QUOTE . $oLeftField->getName(). $o->FIELD_CLOSE_QUOTE ,
					$oJoin->getPredicate(),
				$o->FIELD_OPEN_QUOTE . $oJoinedTable->getTableAlias() . $o->FIELD_CLOSE_QUOTE . '.' . $o->FIELD_OPEN_QUOTE . $oRightField->getName(). $o->FIELD_CLOSE_QUOTE
			);
		} else {
			$sReturn .=  $o->_ON (
					$o->FIELD_OPEN_QUOTE . $oJoinedTable->getTableAlias() . $o->FIELD_CLOSE_QUOTE . '.' . $o->FIELD_OPEN_QUOTE . $oLeftField->getName(). $o->FIELD_CLOSE_QUOTE ,
					$oJoin->getPredicate(),
					$oJoin->getPredicative()
			);

		}
		return $sReturn;
	}

	abstract public function getType();
}