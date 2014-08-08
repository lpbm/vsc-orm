<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.04.27
 */


class KeyPrimary extends IndexA  {
	public function __construct ($mIncomingStuff) {
		/* @var FieldA $oField */
		foreach ($mIncomingStuff as $oField) {
			// enforcing NOT NULL constraints on the components of the primary key
			if ( FieldA::isValid($oField)) {
				$oField->setIsNullable(false);
				$aRet[] = $oField;
			} else {
				throw new IndexException('The object passed can not be used as a primary key.');
			}
		}
		parent::__construct($aRet);
	}

	public function getName () {
		return $this->name;
	}

	public function setName ($sName) {
		$this->name = $sName . '_pk';
	}

	public function getType() {
		return IndexType::PRIMARY;
	}

}
