<?php
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
