<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.05.29
 */

class FieldDateTimeAccess extends SqlFieldAccessA {
	public function getType ( FieldA $oField) {
		return 'DATETIME';
	}

//	protected function escapeValue ( FieldA $oField) {
//		// need a mechanism based on the connection type
//		// TODO
//		return $this->value;
//	}
}
