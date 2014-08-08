<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.05.29
 */


class FieldEnumAccess extends SqlFieldAccessA {
	public function getType ( FieldA $oField) {
		return 'ENUM';
	}

//	protected function escapeValue ( FieldA $oField) {
//		return $this->value;
//	}

//	public function getDefinition ( FieldA $oField) {
//		return	$oField->getType();
//	}
}
