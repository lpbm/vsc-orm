<?php
/**
 * interface for fields and indexes
 * @pacakge domain
 * @subpackage domain
 * @author marius orcsik <marius@habarnam.ro>
 * @date 09.03.29
 */
interface FieldI {
	public function getType ();

	public function setName($sName);

	public function getName();

}
