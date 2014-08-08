<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.03.29
 */


class FieldIntegerAccess extends SqlFieldAccessA {
	public function getType ( FieldA $oField) {
		return 'INTEGER';
	}

//	public function escapeValue ( FieldA $oField) {
//		return (int) $oField->getValue();
//	}
//
	public function getDefinition ( FieldA $oField) {
		// this is totally wrong for PostgreSQL

		return	$this->getType($oField) .
				($this->getConnection()->getType() != ConnectionType::postgresql ? ($oField->getMaxLength() ? '(' . $oField->getMaxLength() . ')' : '') : '').
				($oField->getDefaultValue() !== null || (!$oField->getIsNullable()) ? $this->getConnection()->_NULL($oField->getIsNullable()) : '').
				($oField->hasDefaultValue() ? ' DEFAULT ' . ($oField->getDefaultValue() === null ? $this->getConnection()->_NULL(true) : $oField->getDefaultValue()) : '').
				($this->getConnection()->getType() != ConnectionType::postgresql ? ($oField->getAutoIncrement() ? ' AUTO_INCREMENT' : '') : '');
	}
}
