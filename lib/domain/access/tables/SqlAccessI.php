<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.01.28
 */
namespace orm\domain\access\tables;

use orm\domain\domain\DomainObjectA;

interface SqlAccessI {

	static public function isValidConnection ($oConnection);

	public function save ( DomainObjectA $oInc);

	public function insert ( DomainObjectA $oInc);

	public function update ( DomainObjectA $oInc);

	public function delete ( DomainObjectA $oInc);
}
