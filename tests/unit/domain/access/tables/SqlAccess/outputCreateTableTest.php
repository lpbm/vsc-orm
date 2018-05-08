<?php
namespace unit\domain\access\tables\SqlAccess;

use mocks\domain\access\table\DummyGenericConnection;
use mocks\domain\domain\DummyTable;
use orm\domain\access\tables\SqlAccess;
use vsc\Exception;

class outputCreateTableTest extends \BaseTestCase
{
	/**
	 * @var SqlAccess
	 */
	private $connection;

	public function setUp () {
		return;
		$this->connection = new DummyGenericConnection();
		$this->connection->getConnection()->selectDatabase('test');
	}

	public function tearDown() {}

	public function testCreateSQL () {
		$this->markTestSkipped('Some issues');
		// we should have a separate test for each type of connection
		// the test should be the actual creation
		$o = new DummyTable();
		$createSQL = $this->connection->outputCreateSQL($o);

		$i = $this->connection->getConnection()->query($createSQL);
		$this->assertTrue($i, 'Creation of table failed');
		try {
			$this->connection->getConnection()->query('DROP TABLE ' . $o->getName());
		} catch (Exception $e) {
			// the drop of the table might go wrong - why oh why ?
			throw $e;
		}
	}
}