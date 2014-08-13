<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.05.30
 */
namespace orm\domain\access\indexes;

class KeyIndexAccess extends SqlIndexAccessA {
	public function getType( IndexA $oIndex) {}
	public function getDefinition ( IndexA $oIndex) {
		return	'INDEX ' . $oIndex->getName() . ' (' . $oIndex->getIndexComponents(). ')';
	}
}
