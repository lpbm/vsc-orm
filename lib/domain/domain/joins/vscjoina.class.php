<?php
/**
 * @package vsc_domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
class vscJoinA extends vscClause {
	private $iType = vscJoinType::INNER;

	public function getLeft() {
		return $this->getSubject();
	}
	public function getRight() {
		return $this->getPredicative();
	}

	/**
	 * initializing a JOIN clause
	 *
	 * @param vscFieldA $oLeftField
	 * @param vscFieldA $oRightField
	 */
	public function __construct (vscFieldA $oLeftField, vscFieldA $oRightField = null, $iType = null) {
		parent::__construct($oLeftField, '=', $oRightField);
		if (!is_null($iType)) {
			$this->setType ($iType);
		}
	}

	public function setType ($iType) {
		$this->iType = $iType;
	}

	public function getType () {
		return $this->iType;
	}
}
