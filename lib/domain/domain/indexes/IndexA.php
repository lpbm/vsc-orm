<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.03.19
 */

use vsc\infrastructure\Object;

abstract class IndexA extends Object implements FieldI  {
	protected $name;
	protected $fields = array();

	public function __construct ($mIncomingStuff = null) {
		if ( FieldA::isValid($mIncomingStuff)) {
			$this->setName ($mIncomingStuff->getName());
			$this->addField ($mIncomingStuff);
		} elseif (is_array ($mIncomingStuff)) {
			$this->setName($mIncomingStuff[0]->getName());
			foreach ($mIncomingStuff as $oField) {
				if ( FieldA::isValid($oField))
					$this->addField ($oField);
				else
					throw new IndexException ('The object passed can not be used as an index.');
			}
		} else {
			throw new IndexException ('The data used to instantiate the table\'s primary key is invalid.');
		}
	}

	public function getName () {
		return $this->name;
	}

//	abstract public function setName ($sName);

	public function addField ( FieldA $oField) {
		$this->fields[$oField->getName()] = $oField;
	}

	/**
	 *
	 * @return FieldA[]
	 */
	public function getFields () {
		return $this->fields;
	}

//	public function removeField ( FieldA $oField) {
//		if (isset ($this->fields[$oField->getName()]))
//			unset($this->fields[$oField->getName()]);
//	}

	public function hasField ( FieldA $oField) {
		return (key_exists($oField->getName(), $this->fields) && FieldA::isValid($oField));
	}

	public function getIndexComponents () {
		return implode (', ', array_keys($this->fields));
	}

	static public function isValid ($oIndex) {
		return ($oIndex instanceof static);
	}

	public function __toString() {
		return implode ('.', $this->getFields());
	}
}
