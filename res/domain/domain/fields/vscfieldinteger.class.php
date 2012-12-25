<?php
/**
 * @package vsc_domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.03.29
 */
import ('domain/domain/fields');

class vscFieldInteger extends vscFieldA {
	protected  $maxLength = 11;
	protected  $autoIncrement = false;

	public function isInt (vscFieldA $oField) {
		return ($oField instanceof self);
	}

	public function getType () {
		return vscFieldType::INTEGER;
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
