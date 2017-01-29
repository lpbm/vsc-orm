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
	 * @var DummyPostgresConnectionAccess
	 */
	private $connection;

	public function setUp () {
		$this->connection = new DummyPostgresConnection();
		$this->connection->getConnection()->selectDatabase('test');
	}

	public function tearDown() {}

	public function testInstantiation () {
		$this->assertInstanceOf(SqlAccessA::class, $this->connection);
		$this->assertInstanceOf(DummyPostgresConnection::class, $this->connection);
	}

	public function testGetConnection () {
		$this->connection->setConnection (ConnectionFactory::connect(ConnectionType::mysql));
		$this->assertInstanceOf(PostgreSql::class, $this->connection->getConnection());
	}
}
