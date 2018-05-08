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
use mocks\domain\access\table\DummyPostgresConnection;
use mocks\domain\connections\DummyConnection;
use orm\domain\access\tables\SqlAccessA;
use orm\domain\connections\ConnectionFactory;
use orm\domain\connections\ConnectionType;
use orm\domain\connections\PostgreSql;

/**
 * Class AccessTest
 * @package domain\access
 */
class AccessTest extends \BaseTestCase {
	/**
	 * @var DummyPostgresConnection
	 */
	private $connection;

	public function setUp () {
		if (!extension_loaded('pgsql')) {
			$this->markTestSkipped('Postgres extension not-loaded');
		}
		$this->connection = new DummyPostgresConnection();
		$this->connection->getConnection()->selectDatabase('test');
	}

	public function tearDown() {}

	public function testInstantiation () {
		if (!extension_loaded('pgsql')) {
			$this->markTestSkipped('Postgres extension not-loaded');
		}
		$this->assertInstanceOf(SqlAccessA::class, $this->connection);
		$this->assertInstanceOf(DummyPostgresConnection::class, $this->connection);
	}

	public function testGetConnection () {
		if (!extension_loaded('pgsql')) {
			$this->markTestSkipped('Postgres extension not-loaded');
		}
		$o = new DummyConnection();
		$this->connection->setConnection ($o);
		$this->assertSame($o, $this->connection->getConnection());
	}
}
