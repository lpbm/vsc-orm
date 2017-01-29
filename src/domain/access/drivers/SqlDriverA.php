<?php
/**
 * @pacakge \orm\domain\access\drivers
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.01
 */
namespace orm\domain\access\drivers;

use vsc\infrastructure\Base;

abstract class SqlDriverA extends Base implements SqlDriverI {
	public 	$STRING_OPEN_QUOTE,
			$STRING_CLOSE_QUOTE,
			$FIELD_OPEN_QUOTE,
			$FIELD_CLOSE_QUOTE,
			$TRUE,
			$FALSE;
}
