<?php
abstract class vscAccessEntityA extends vscObject {
	private $oConnection;
	private $oGrammarHelper;

	public function setConnection (vscConnectionA $oConnection) {
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