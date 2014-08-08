<?php
/**
 * @pacakge domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
class JoinA extends Clause {
	private $iType = JoinType::INNER;

	public function getLeft() {
		return $this->getSubject();
	}
	public function getRight() {
		return $this->getPredicative();
	}

	/**
	 * initializing a JOIN clause
	 *
	 * @param FieldA $oLeftField
	 * @param FieldA $oRightField
	 */
	public function __construct ( FieldA $oLeftField, FieldA $oRightField = null, $iType = null) {
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
