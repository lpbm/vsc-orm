<?php
/* Db constants
 -----------------------*/
//define ('DB_TYPE', 				'mysql');
//define ('DB_HOST', 				'localhost');
//define ('DB_USER', 				'root');
//define ('DB_PASS', 				'ASD');
//define ('DB_NAME', 				'b');
//
use \_fixtures\access\testConnection;

class AccessTest extends \PHPUnit_Framework_TestCase {
	private $connection;

	public function setUp () {
		$this->connection = new testConnection();
		$this->connection->getConnection()->selectDatabase('test');
	}

	public function tearDown() {}

	public function test_Instantiation () {
		$this->assertInstanceOf('\\orm\\domain\\domain\\DomainObjectA', $this->connection);
		$this->assertInstanceOf('\\orm\\domain\\domain\\DomainObject', $this->connection);
	}

	public function testGetConnection () {
		$this->connection->setConnection ( ConnectionFactory::connect('mysql'));
		$this->assertIsA($this->connection->getConnection(), 'mySqlIm');
	}

	public function testCreateSQL () {
		// we should have a separate test for each type of connection
		// the test should be the actual creation
		$o = new dummyTable();
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
