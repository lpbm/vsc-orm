<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.05.29
 */
namespace orm\domain\access\fields;

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
