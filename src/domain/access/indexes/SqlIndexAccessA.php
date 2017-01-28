<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.05.29
 */
namespace orm\domain\access\indexes;

use orm\domain\access\AccessEntityA;
use orm\domain\domain\indexes\IndexA;

abstract class SqlIndexAccessA extends AccessEntityA {
	abstract public function getType( IndexA $oIndex);
}
