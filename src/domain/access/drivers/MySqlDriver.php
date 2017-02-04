<?php
namespace orm\domain\access\drivers;

use orm\domain\domain\fields\FieldA;

class MySqlDriver extends SqlGenericDriver {
	public $STRING_OPEN_QUOTE = '"',
		$STRING_CLOSE_QUOTE = '"',
		$FIELD_OPEN_QUOTE = '`',
		$FIELD_CLOSE_QUOTE = '`',
		$TRUE = '1',
		$FALSE = '0';

	public function _SET(){
		return ' SET ';
	}

	/**
	 * @return string
	 */
	public function _AND (){
		return ' AND ';
	}

	/**
	 * @return string
	 */
	public function _OR (){
		return ' OR ';
	}

	/**
	 * @param mixed $orderBys
	 * @param mixed $aDirections
	 * @return string
	 */
	public function _ORDER ($orderBys = null, $aDirections = null){
		if (empty($orderBys)) {
			return '';
		}
		$sOrderBys = '';
		if (!is_array($orderBys)) {
			$orderBys = array($orderBys);
		}

		foreach ($orderBys as $key => $sField) {
			$sOrderBys .= $this->FIELD_OPEN_QUOTE . $sField . $this->FIELD_CLOSE_QUOTE . ' ';
			if (is_array ($aDirections)) {
				$sOrderBys .= isset($aDirections[$key]) ? $aDirections[$key] : '';
			}
		}

		return ' ORDER BY ' . $sOrderBys;
	}

	public function _NULL ($bIsNull = true) {
		// ?
		return (!$bIsNull ? ' NOT ' : ' ') . 'NULL';
	}
}
