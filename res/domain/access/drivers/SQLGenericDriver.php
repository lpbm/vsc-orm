<?php
class SQLGenericDriver extends sqlDriverA {
	/**
	 *
	 * @param array $incObj = array (array('field1','alias1),array('field2','alias2),...)
	 * @return string
	 */
	public function _SELECT ($incObj){
		if (empty ($incObj)) {
			return '';
		}

		$retStr = 'SELECT ';
		return $retStr . ' ' . $incObj . ' ';
	}

	public function _DELETE($sIncName) {
		return ' DELETE FROM ' . $sIncName . ' ';
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
			$incData = implode( "\n".', ',$incData);
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

	/**
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
	 * TODO make it receive an array of tdoHabstractFields
	 * (see _SELECT)
	 *
	 * @param string[] $colName
	 * @return string
	 */
	public function _GROUP ($incObj = null){
		if (empty ($incObj)) {
			return '';
		}

		$retStr = ' GROUP BY ';
		return $retStr.' '.$incObj;
	}

	public function _ORDER ($orderBys = null){
		if (empty($orderBys)) {
			return '';
		}
		$retStr = ' ORDER BY ';

		return $retStr.$orderBys;
	}

	public function _WHERE ($clause) {
		return ' WHERE '.$clause;
	}

	public function _NULL ($bIsNull = true) {
		// ?
		return (!$bIsNull ? ' NOT ' : ' ') . 'NULL';
	}
}
