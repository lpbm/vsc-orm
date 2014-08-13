<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.05.29
 */
namespace orm\domain\access\indexes;

abstract class SqlIndexAccessA extends AccessEntityA {
	abstract public function getType( IndexA $oIndex);
}
