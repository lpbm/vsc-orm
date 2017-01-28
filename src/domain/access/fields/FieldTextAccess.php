<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author Marius Orcsik <marius@habarnam.ro>
 * @date 09.05.29
 */
namespace orm\domain\access\fields;

use orm\domain\domain\fields\FieldA;
use orm\domain\connections\ConnectionType;

class FieldTextAccess extends SqlFieldAccessA {
	public function getType ( FieldA $oField) {
		if ($oField->getMaxLength() > 255 || $oField->getMaxLength() === null) {
			return 'TEXT';
		} else {
			return 'VARCHAR';
		}
	}

//	protected function escapeValue ( FieldA $oField) {
//		return $this->getConnection()->escape($oField->getValue());
//	}

	public function getDefinition ( FieldA $oField) {
		// this is totally wrong for PostgreSQL
		return	$this->getType($oField) .
				($this->getType($oField) != 'TEXT' ? ($oField->getMaxLength() ? '(' . $oField->getMaxLength() . ')' : '') : '' ).
				($this->getConnection()->getType() != ConnectionType::postgresql ? ($oField->getEncoding() ? ' CHARACTER SET ' . $oField->getEncoding() : '') : '') .
				($oField->getDefaultValue() !== null || (!$oField->getIsNullable()) ? $this->getGrammarHelper()->_NULL($oField->getIsNullable()) : '').
				($oField->hasDefaultValue() ? ' DEFAULT ' . ($oField->getDefaultValue() === null ? $this->getGrammarHelper()->_NULL(true) : $oField->getDefaultValue()) : '');
	}
}
