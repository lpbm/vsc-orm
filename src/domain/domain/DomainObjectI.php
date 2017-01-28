<?php
/**
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2010.01.28
 */
namespace orm\domain\domain;

use orm\domain\domain\fields\FieldA;
use orm\domain\domain\indexes\IndexA;

interface DomainObjectI {
	/**
	 * @return FieldA[]
	 */
	public function getFields();

	/**
	 * gets all the column names as an array
	 * @return string[]
	 */
	public function getFieldNames ($bWithAlias = false);

	/**
	 * @param bool $bWithPrimaryKey
	 * @return IndexA[]
	 */
	public function getIndexes ($bWithPrimaryKey = false);
}
