<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.09.19
 */

use vsc\infrastructure\Object;

class AccessOrders extends Object {
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

	abstract public function getType();
}
