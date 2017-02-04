<?php
namespace orm\domain\access\drivers;

class MySqlDriver extends SqlGenericDriver {
	const STRING_OPEN_QUOTE = '"';
	const STRING_CLOSE_QUOTE = '"';
	const FIELD_OPEN_QUOTE = '`';
	const FIELD_CLOSE_QUOTE = '`';
	const TRUE = '1';
	const FALSE = '0';
}
