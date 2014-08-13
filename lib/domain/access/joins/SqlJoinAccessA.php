<?php
/**
 * @pacakge \orm\domain\access\joins
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
namespace orm\domain\access\joins;

abstract class SqlJoinAccessA extends AccessEntityA {

	public function getDefinition ( JoinA $oJoin) {
		/* @var $o mySQLDriver */
		$o = $this->getGrammarHelper();

		$oRightField = $oJoin->getRight();
		$oLeftField = $oJoin->getLeft();
		if ( FieldA::isValid($oRightField)) {
			$oJoinedTable = $oRightField->getParent();
			$oJoinOnTable = $oLeftField->getParent();
		} else {
			$oJoinedTable = $oJoin->getLeft()->getParent();
		}

		$sReturn = ' ' . $o->_JOIN($this->getType()) . $o->FIELD_OPEN_QUOTE . $oJoinedTable->getTableName() . $o->FIELD_CLOSE_QUOTE . $o->_AS ($o->FIELD_OPEN_QUOTE . $oJoinedTable->getTableAlias() . $o->FIELD_CLOSE_QUOTE);
		if ( FieldA::isValid($oRightField)) {
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
