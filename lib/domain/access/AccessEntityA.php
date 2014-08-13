<?php
/**
 * @pacakge \orm\domain\access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.06.02
 */
namespace orm\domain\access;

use orm\domain\connections\ConnectionA;
use vsc\infrastructure\Object;

abstract class AccessEntityA extends Object {
	private $oConnection;
	private $oGrammarHelper;

	public function setConnection ( ConnectionA $oConnection) {
		$this->oConnection = $oConnection;
	}

	public function getConnection () {
		return $this->oConnection;
	}

	public function setGrammarHelper ($oGrammarHelper) {
		$this->oGrammarHelper = $oGrammarHelper;
	}

	public function getGrammarHelper () {
		return $this->oGrammarHelper;
	}
}
