<?php
/**
 * @pacakge \orm\domain\access\drivers
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.01
 */
namespace orm\domain\access\drivers;

use vsc\infrastructure\Base;

abstract class SqlDriverA extends Base implements SqlDriverI {
	/**
	 * @param string $sFieldName
	 * @return string
	 */
	abstract protected function getQuotedFieldName($sFieldName);

	/**
	 * @param string $sValue
	 * @return mixed
	 */
	abstract protected function getQuotedValue($sValue);
}
