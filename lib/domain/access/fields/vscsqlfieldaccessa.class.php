<?php
/**
 * @package vsc_domain
 * @subpackage access
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.05.29
 */
abstract class vscSqlFieldAccessA extends vscAccessEntityA {
	public function escapeValue (vscFieldA $oField) {
		/* @var $o sqlDriverA */
		$o = $this->getGrammarHelper();
		$mValue	=  $this->getConnection()->escape($oField->getValue());

		if (is_null($mValue) || $mValue == 'NULL') {
			return $o->_NULL(true);
		} elseif (is_numeric($mValue)) {
			$sCondition = $mValue;
		} elseif (is_string($mValue) ) {
			// this should be moved to the sql driver
			$sCondition = $o->STRING_OPEN_QUOTE . $mValue . $o->STRING_CLOSE_QUOTE;
		}

		return $sCondition;
	}

	public function getDefinition (vscFieldA $oField) {

		$sRet = $this->getType($oField);
		if (!$oField->getIsNullable()) {
			$sRet .= $this->getGrammarHelper()->_NULL($oField->getIsNullable());
		} elseif ($oField->getDefaultValue() === null) {
			$sRet .= ' DEFAULT ' . $this->getGrammarHelper()->_NULL(true);
		} elseif ($oField->hasDefaultValue()) {
			$sRet .= ' DEFAULT ' . $oField->getDefaultValue();
		}


		return $sRet;
	}

	public function getNameWithValue (vscFieldA $oField) {
		return $this->getQuotedFieldName($oField) . ' = ' . $this->escapeValue($oField);
	}

	public function getQuotedFieldName (vscFieldA $oField) {
		$o = $this->getGrammarHelper();

		return $o->FIELD_OPEN_QUOTE . $oField->getName() . $o->FIELD_CLOSE_QUOTE;
	}

	abstract public function getType(vscFieldA $oField);
}