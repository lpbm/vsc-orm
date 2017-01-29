<?php
namespace orm\domain\access\drivers;

use orm\domain\domain\DomainObjectI;

class SqlGenericDriver extends SqlDriverA {
	/**
	 *
	 * @param array $incObj = array (array('field1','alias1),array('field2','alias2),...)
	 * @return string
	 */
	public function _SELECT ($incObj = null){
		if (empty ($incObj)) {
			return '';
		}

		if (is_array($incObj)) {
			$selectables = implode(', ', $incObj);
		}
		if (is_scalar($incObj)) {
			$selectables = $incObj;
		}
		$retStr = 'SELECT';
		return $retStr . ' ' . $selectables . ' ';
	}

	/**
	 * @param $sName
	 * @return string
	 */
	public function _DELETE($sName = null) {
		if (empty ($sName)) {
			return '';
		}
		return 'DELETE FROM ' . $sName . ' ';
	}

	/**
	 * @param $sName
	 * @return string
	 */
	public function _CREATE ($sName = null){
		if (empty ($sName)) {
			return '';
		}
		return 'CREATE TABLE ' . $sName . ' ';
	}

	/**
	 * @return string
	 */
	public function _SET() {
		return ' SET ';
	}

	/**
	 * @param $incData
	 * @return string
	 */
	public function _INSERT ($incData = null) {
		if (empty ($incData)) {
			return '';
		}
		return 'INSERT INTO ' . $incData . ' ';
	}

	/**
	 * @param $incData
	 * @return string
	 */
	public function _VALUES ($incData = null) {
		if (empty ($incData)) {
			return '';
		}
		return ' VALUES ' . $incData;
	}

	/**
	 * @param $sName
	 * @return string
	 */
	public function _UPDATE ($sName = null){
		if (empty ($sName)) {
			return '';
		}
		return 'UPDATE '. $sName . ' ';
	}

	/**
	 * returns the FROM tables part of the query
	 *
	 * @param string[] $incData - table names
	 * @return string
	 */
	public function _FROM ($incData = null){
		if (empty ($incData)) {
			return '';
		}
		if (is_array($incData)) {
			$incData = implode( ",\n",$incData);
		}

		return ' FROM ' . $incData . ' ';
	}

	/**
	 * @return string
	 */
	public function _AND () {
		return ' AND ';
	}

	/**
	 * @return string
	 */
	public function _OR () {
		return ' OR ';
	}

	/**
	 * @param $type
	 * @return string
	 */
	public function _JOIN ($type = null) {
		if (empty($type)) {
			return '';
		}
		return $type . ' JOIN ';
	}

	/**
	 * @return string
	 */
	public function _AS ($alias = null) {
		if (empty($alias)) {
			return '';
		}
		return ' AS ' . $alias;
	}

	/**
	 * @param int $start
	 * @param int $end
	 * @return string
	 */
	public function _LIMIT ($start = 0, $end = 0) {
		if (!empty($end)) {
			return ' LIMIT '.(int)$start . ', '.(int)$end;
		} elseif (!empty ($start)) {
			return ' LIMIT '.(int)$start;
		}
		return '';
	}

	/**
	 * TODO make it receive an array of tdoHabstractFields
	 * (see _SELECT)
	 *
	 * @param DomainObjectI $incObj
	 * @internal param \string[] $colName
	 * @return string
	 */
	public function _GROUP ($incObj = null){
		if (empty ($incObj)) {
			return '';
		}

		$retStr = ' GROUP BY ';
		return $retStr . $incObj;
	}

	/**
	 * @param string $orderBys
	 * @return string
	 */
	public function _ORDER ($orderBys = null){
		if (empty($orderBys)) {
			return '';
		}
		$retStr = ' ORDER BY ';

		return $retStr . $orderBys;
	}

	/**
	 * @param string $clause
	 * @return string
	 */
	public function _WHERE ($clause = null) {
		if (empty($clause)) {
			return '';
		}
		return ' WHERE ' . $clause;
	}

	/**
	 * @param bool $bIsNull
	 * @return string
	 */
	public function _NULL ($bIsNull = true) {
		// ?
		return (!$bIsNull ? ' NOT ' : ' ') . 'NULL';
	}
}
