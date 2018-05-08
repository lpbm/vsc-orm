<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.09.19
 */
namespace orm\domain\access\orders;

use orm\domain\connections\ConnectionA;
use vsc\infrastructure\Base;

class AccessOrders extends Base{
	private $oConnection;
	/**
	 * @param ConnectionA $oConnection
	 * @return null
	 */
	public function setConnection ( ConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}

	/**
	 * @return ConnectionA
	 */
	public function getConnection () {
		return $this->oConnection;
	}

	public function getDefinition ($aOrderBys) {
		$o = $this->getConnection();
		$sOrderBy = '';

		foreach ($aOrderBys as $aOrderBy) {
			$oField = $aOrderBy[0];
			$sDirection = $aOrderBy[1];

			$sOrderBy .= ($oField->hasAlias() ? $oField->getAlias() : $oField->getName()) . $sDirection;
		}
		return  ' ' .$o->_ORDER($sOrderBy);
	}

//	abstract public function getType();
}
