<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.04.27
 */
namespace orm\domain\domain\indexes;

use orm\domain\ExceptionIndex;
use orm\domain\domain\fields\FieldI;
use orm\domain\domain\fields\FieldA;

class KeyIndex extends IndexA  {
	public function __construct ( FieldI $oField) {
		$mIncomingStuff = func_get_args();

		$aRet = array();
		if ( FieldA::isValid($mIncomingStuff)) {
			$mIncomingStuff->setIsNullable(false);
			parent::__construct ($mIncomingStuff);
		} elseif (is_array($mIncomingStuff)) {
			/* @var FieldA $oField */
			foreach ($mIncomingStuff as $oField) {
				// enforcing NOT NULL constraints on the components of the primary key
				if ( FieldA::isValid($oField)) {
					$oField->setIsNullable(false);
					$aRet[] = $oField;
				} else {
					throw new ExceptionIndex('The object passed can not be used as a primary key.');
				}
			}
			parent::__construct($aRet);
		}
	}

	public function setName ($sName) {
		$this->name = $sName . '_idx';
	}

	public function getType() {
		return IndexType::INDEX;
	}

}
