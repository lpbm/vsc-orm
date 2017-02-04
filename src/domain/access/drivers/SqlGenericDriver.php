<?php
namespace orm\domain\access\drivers;

use orm\domain\domain\DomainObjectI;

class SqlGenericDriver extends SqlDriverA implements SqlDriverI  {
	/**
	 *
	 * @param array $incObj = array (array('field1','alias1),array('field2','alias2),...)
	 * @return string
	 */
	public function _SELECT ($incObj = null){
		if (empty ($incObj)) {
			return '';
		}

		$aSelectables = [];
		if (is_array($incObj)) {
			foreach ($incObj as $key => $field) {
				$incObj[$key] = $this->getQuotedFieldName($field);
			}
			$aSelectables = implode(', ', $incObj);
		}
		if (is_scalar($incObj)) {
			$aSelectables = $this->getQuotedFieldName($incObj);
		}
		$retStr = 'SELECT';
		return $retStr . ' ' . $aSelectables . ' ';
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
	 * @param string $subject
	 * @param string $predicate
	 * @param string $predicative
	 * @return string
	 */
	public function _ON ($subject, $predicate, $predicative) {
		return ' ON ' . $subject . ' '. $predicate . ' ' . $predicative;
	}

	/**
	 * @param null $alias
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
	 * TODO make it receive an array of AbstractField
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
			$sOrderBys .= $this->getQuotedFieldName($sField) . ' ';
			if (is_array ($aDirections)) {
				$sOrderBys .= isset($aDirections[$key]) ? $aDirections[$key] : '';
			}
		}

		return ' ORDER BY ' . $sOrderBys;
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

	/**
	 * @param string $sFieldName
	 * @return string
	 * @throws ExceptionDriver
	 */
	protected function getQuotedFieldName ($sFieldName)
	{
		if (strpos($sFieldName, '.') !== false) {
			$aElements = explode('.', $sFieldName);
		} else {
			$aElements = [$sFieldName];
		}
		if (count($aElements) > 3) {
			// this shouldn't really happen
			throw new ExceptionDriver('Too many dots in column identifier ' . $sFieldName);
		}
		foreach ($aElements as $sElement) {
			$aQuotedElements[] = static::FIELD_OPEN_QUOTE . $sElement . static::FIELD_CLOSE_QUOTE;
		}

		return implode('.', $aQuotedElements);
	}

	/**
	 * @param string $sValue
	 * @return mixed
	 */
	protected function getQuotedValue ($sValue)
	{
		if (is_string($sValue)) {
			return static::STRING_OPEN_QUOTE . $sValue . static::STRING_CLOSE_QUOTE;
		}
		return $sValue;
	}
}
