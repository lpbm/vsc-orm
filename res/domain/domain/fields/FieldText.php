<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author Marius Orcsik <marius@habarnam.ro>
 * @date 09.05.01
 */


class FieldText extends FieldA {
	protected  $maxLength = 255;
	protected  $encoding = 'UTF8';

	public function getType () {
		return FieldType::TEXT;
	}

	protected function escape () {
		// need a mechanism based on the connection type
		// TODO
		return $this->value;
	}

	public function getEncoding () {
		return $this->encoding;
	}

	public function setEncoding ($sEncoding) {
		$this->encoding = $sEncoding;
	}

}
