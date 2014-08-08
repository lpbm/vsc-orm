<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.03.29
 */


class FieldInteger extends FieldA {
	protected  $maxLength = 11;
	protected  $autoIncrement = false;

	public function isInt ( FieldA $oField) {
		return ($oField instanceof self);
	}

	public function getType () {
		return FieldType::INTEGER;
	}

	protected function escape () {
		return (int) $this->value;
	}

	/**
	 * @param bool $bIsAutoIncrement
	 * @return void
	 */
	public function setAutoIncrement ($bIsAutoIncrement) {
		$this->autoIncrement = (bool)$bIsAutoIncrement;
		$this->setIsNullable(false);
	}

	public function getAutoIncrement () {
		return $this->autoIncrement;
	}

}
