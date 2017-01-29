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

	public function _DELETE($sName) {
		return ' DELETE FROM ' . $sName . ' ';
	}

	public function _CREATE ($sName){
		return ' CREATE TABLE ' . $sName . ' ';
	}

	public function _SET(){
		return ' SET ';
	}

	public function _INSERT ($incData){
		if (empty ($incData)) {
			return '';
		}
		return ' INSERT INTO '.$incData . ' ';
	}

	public function _VALUES ($incData) {
		return ' VALUES ' . $incData;
	}

	public function _UPDATE ($sTable){
		return ' UPDATE '. $sTable;
	}

	/**
	 * returns the FROM tabl...es part of the query
	 *
	 * @param string[] $incData - table names
	 * @return string
	 */
	public function _FROM ($incData){
		if (empty ($incData)) {
			return '';
		}
		if (is_array($incData)) {
			$incData = implode("\n".', ',$incData);
		}

		return ' FROM '.$incData.' ';
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

	public function _JOIN ($type) {
		return $type . ' JOIN ';
	}

	public function _ON ($subject, $predicate, $predicative) {
		return ' ON ' . $subject . ' '. $predicate . ' ' . $predicative;
	}

	/**
	 * @param $str
	 * @return string
	 */
	public function _AS ($str){
		return ' AS '.$str;
	}

	public function _LIMIT ($start, $end = 0){
		if (!empty($end)) {
			return ' LIMIT '.(int)$start . ', '.(int)$end;
		} elseif (!empty ($start)) {
			return ' LIMIT '.(int)$start;
		} else {
			return '';
		}
	}

	/**
	 * TODO make it receive an array of AbstractFields
	 * (see _SELECT)
	 *
	 * @param FieldA $incObj
	 * @return string
	 */
	public function _GROUP ($incObj = null){
		if (empty ($incObj)) {
			return '';
		}

		$retStr = ' GROUP BY ';
		return $retStr.' '.$incObj;
	}

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

	public function _WHERE ($clause) {
		return ' WHERE '.$clause;
	}

	public function _NULL ($bIsNull = true) {
		// ?
		return (!$bIsNull ? ' NOT ' : ' ') . 'NULL';
	}
}
