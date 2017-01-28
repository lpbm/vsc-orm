<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.05.01
 */
namespace orm\domain\domain\fields;

class FieldDateTime extends FieldA {
	protected  $maxLength = null; // arbitrary chosen, > strlen(YYYY-MM-DD GG:II:SS)

	public function getType () {
		return FieldType::DATETIME;
	}

	protected function escape () {
		// need a mechanism based on the connection type
		// TODO
		return $this->value;
	}

	public function format ($sFormat) {
		return static::parse ($sFormat, $this->value);
	}

	static public function parse ($mValue, $sFormat = '%Y-%m-%d %T') {
		if (is_string($mValue))
			$mValue = strtotime($mValue);

		return strftime($sFormat, $mValue);
	}

}
