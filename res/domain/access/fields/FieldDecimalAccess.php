<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.03.29
 */
namespace orm\domain\access\fields;

use orm\domain\domain\fields\FieldA;

class FieldDecimalAccess extends SqlFieldAccessA {
	public function getType ( FieldA $oField) {
		return 'DECIMAL';
	}

//	public function escapeValue ( FieldA $oField) {
//		return (int) $oField->getValue();
//	}
//
	public function getDefinition ( FieldA $oField) {
		// this is totally wrong for PostgreSQL
		return	$this->getType($oField) .
				($oField->getMaxLength() ? '(' . $oField->getMaxLength() . ', ' . $oField->getDecimals() . ')' : '') .
				($oField->getDefaultValue() !== null || (!$oField->getIsNullable()) ? $this->getConnection()->_NULL($oField->getIsNullable()) : '');
	}
}
