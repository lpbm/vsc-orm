<?php
/**
 * @pacakge domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.09.19
 */
namespace orm\domain\access\groups;

use orm\domain\connections\ConnectionA;
use vsc\infrastructure\Object;

class AccessGroups extends Object {
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

	public function getDefinition ($aGroupBys) {
		$o = $this->getConnection();
		$sGroupBy = '';

		foreach ($aGroupBys as $aGroupBy) {
			$oField 	= $aGroupBy[0];
			$sDirection = $aGroupBy[1];

			$sGroupBy .= ($oField->hasAlias() ? $oField->getAlias() : $oField->getName()) . $sDirection;
		}
		return  ' ' .$o->_GROUP($sGroupBy);
	}

//	abstract public function getType();
}
