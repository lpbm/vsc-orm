<?php
/**
 * class to abstract a where clause in a SQL query
 * TODO: add possibility of complex wheres: (t1 condition1 OR|AND|XOR t2.condition2)
 * TODO: abstract the condition part of a where clause - currently string based :D
 * @pacakge domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.01
 */
namespace orm\domain\domain\clauses;

use orm\domain\domain\fields\FieldA;
use vsc\infrastructure\Object;

class Clause extends Object {
	private	$mSubject;
	private	$sPredicate;
	private	$mPredicative;

	public function getSubject() {
		return $this->mSubject;
	}
	public function getPredicate() {
		return $this->sPredicate;
	}
	public function getPredicative() {
		return $this->mPredicative;
	}

	/**
	 * initializing a WHERE|ON clause
	 *
	 * @param Clause|FieldA $mSubject
	 * @param string|null $sPredicate
	 * @param mixed $mPredicative
	 */
	public function __construct ($mSubject, $sPredicate = null, $mPredicative = null) {
		// I must be careful with the $mSubject == 1 because (int)object == 1
		if (($mSubject === 1 || is_string($mSubject)) && $sPredicate == null && $mPredicative == null) {
			$this->mSubject		= $mSubject;
			$this->sPredicate	= '';
			$this->mPredicative	= '';
			return;
		}

		if ( FieldA::isValid($mSubject) && is_null($mPredicative) && $sPredicate == '=') {
			$sPredicate = 'IS';
		}

		if ( FieldA::isValid($mSubject) || Clause::isValid($mSubject)) {
			$this->mSubject 	= $mSubject;
			$this->mPredicative	= $mPredicative;
			if ($this->validPredicate ($sPredicate)) {
				$this->sPredicate	= $sPredicate;
			} else {
				$this->sPredicate	= $sPredicate;
			}
		} else {
			$this->mSubject		= '';
			$this->sPredicate	= '';
			$this->mPredicative	= '';

			return;
		}
	}

	public function validPredicate ($sPredicate) {
		return true;
	}
}