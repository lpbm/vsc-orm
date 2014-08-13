<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.04.27
 */
namespace orm\domain\domain\indexes;

class KeyUnique extends KeyIndex  {
	public function setName ($sName) {
		$this->name = $sName . '_unq';
	}

	public function getType() {
		return IndexType::UNIQUE;
	}

}
