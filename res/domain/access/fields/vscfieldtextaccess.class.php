<?php
/**
 * @package vsc_domain
 * @subpackage access
 * @author Marius Orcsik <marius@habarnam.ro>
 * @date 09.05.29
 */
import ('domain/access/fields');

class vscFieldTextAccess extends vscSqlFieldAccessA {
	public function getType (vscFieldA $oField) {
		if ($oField->getMaxLength() > 255 || $oField->getMaxLength() === null) {
			return 'TEXT';
		} else {
			return 'VARCHAR';
		}
	}

//	protected function escapeValue (vscFieldA $oField) {
//		return $this->getConnection()->escape($oField->getValue());
//	}

	public function getDefinition (vscFieldA $oField) {
		// this is totally wrong for PostgreSQL
		return	$this->getType($oField) .
				($this->getType($oField) != 'TEXT' ? ($oField->getMaxLength() ? '(' . $oField->getMaxLength() . ')' : '') : '' ).
				($this->getConnection()->getType() != vscConnectionType::postgresql ? ($oField->getEncoding() ? ' CHARACTER SET ' . $oField->getEncoding() : '') : '') .
				($oField->getDefaultValue() !== null || (!$oField->getIsNullable()) ? $this->getGrammarHelper()->_NULL($oField->getIsNullable()) : '').
				($oField->hasDefaultValue() ? ' DEFAULT ' . ($oField->getDefaultValue() === null ? $this->getGrammarHelper()->_NULL(true) : $oField->getDefaultValue()) : '');
	}
}