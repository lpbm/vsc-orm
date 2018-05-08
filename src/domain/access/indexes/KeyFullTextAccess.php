<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.05.30
 */
namespace orm\domain\access\indexes;

use orm\domain\connections\ConnectionType;
use orm\domain\domain\indexes\IndexA;

class KeyFullTextAccess extends SqlIndexAccessA {
	public function getType( IndexA $oIndex) {}

	/**
	 * @todo make sure we don't take into account this type of index for other than mysql with myIsam
	 */
	public function getDefinition ( IndexA $oIndex) {
		// this is totally wrong for PostgreSQL
		if ($this->getConnection()->getType() == ConnectionType::postgresql) return '';
		return	'FULLTEXT INDEX ' . $oIndex->getName() . ' (' . $oIndex->getIndexComponents(). ')';
	}
}
