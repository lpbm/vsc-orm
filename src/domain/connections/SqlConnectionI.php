<?php
/**
 * @pacakge \orm\domain\connections
 * @author marius orcsik <marius@habarnam.ro>
 * @date 2017.01.31
 */
namespace orm\domain\connections;


interface SqlConnectionI
{
	public function getType ();

	public function close ();

	public function getScalar();

	public function startTransaction($bAutoCommit = false);

	public function rollBackTransaction();

	public function commitTransaction();

	public function escape($incData);

	public function query($query);

	public function getRow();

	public function getArray();

	public function getFirst();
}
