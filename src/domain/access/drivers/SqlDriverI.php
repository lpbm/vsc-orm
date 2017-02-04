<?php
/**
 * @pacakge \orm\domain\access\drivers
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2017.01.29
 */
namespace orm\domain\access\drivers;

interface SqlDriverI
{
	const STRING_OPEN_QUOTE = '';
	const STRING_CLOSE_QUOTE = '';
	const FIELD_OPEN_QUOTE = '';
	const FIELD_CLOSE_QUOTE = '';
	const TRUE = '';
	const FALSE = '';

	public function _SELECT($incObj);
	
	public function _DELETE($sName);
	
	public function _CREATE($sIncName);
	
	public function _SET();
	
	public function _INSERT($incOb);
	
	public function _VALUES ($incData);
	
	public function _UPDATE($incOb);
	
	public function _FROM($incData);
	
	public function _AND();
	
	public function _OR();
	
	public function _JOIN ($type);
	
	public function _AS($str);
	
	public function _LIMIT($start, $end = 0);
	
	public function _GROUP($incObj = null);
	
	public function _ORDER($orderBys = null);
	
	public function _WHERE ($clause);
	
	public function _NULL ($bIsNull = true);
}