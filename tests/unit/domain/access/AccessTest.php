<?php
namespace domain\access;
/* Db constants
 -----------------------*/
//define ('DB_TYPE', 				'mysql');
//define ('DB_HOST', 				'localhost');
//define ('DB_USER', 				'root');
//define ('DB_PASS', 				'ASD');
//define ('DB_NAME', 				'b');
//
use mocks\domain\access\DummyConnectionAccess;
use mocks\domain\domain\DummyTable;
use orm\domain\access\AccessA;
use orm\domain\connections\ConnectionFactory;
use orm\domain\connections\ConnectionType;
use orm\domain\connections\MySqlIm;
use vsc\Exception;

class AccessTest extends \PHPUnit_Framework_TestCase {
	/**
	 * @var DummyConnectionAccess
	 */
	private $connection;

	public function setUp () {
		$this->connection = new DummyConnectionAccess();
//		$this->connection->getConnection()->selectDatabase('test');
	}

	public function tearDown() {}

	public function test_Instantiation () {
		$this->assertInstanceOf(AccessA::class, $this->connection);
		$this->assertInstanceOf(DummyConnectionAccess::class, $this->connection);
	}

	public function testGetConnection () {
		$this->connection->setConnection ( ConnectionFactory::connect(ConnectionType::mysql));
		$this->assertIsA($this->connection->getConnection(), MySqlIm::class);
	}

	public function testCreateSQL () {
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
