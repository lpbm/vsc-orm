<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.05.01
 */
namespace orm\domain\domain\indexes;

class KeyFullText extends KeyUnique {
	public function setName ($sName) {
		$this->name = $sName . '_tx';
	}

	public function getType() {
		return IndexType::FULLTEXT;
	}
}
