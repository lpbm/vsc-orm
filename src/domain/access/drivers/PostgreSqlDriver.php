<?php
namespace orm\domain\access\drivers;

class PostgreSqlDriver extends SqlGenericDriver {
	const STRING_OPEN_QUOTE = '\'';
	const STRING_CLOSE_QUOTE = '\'';
	const FIELD_OPEN_QUOTE = '"';
	const FIELD_CLOSE_QUOTE = '"';
	const TRUE = 'true';
	const FALSE = 'false';

	public function _SET(){
		return ' ';
	}
}
