<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class deleteTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_DELETEEmptyFields() {
		$this->assertEquals('', $this->driver->_DELETE(''));
	}

	public function test_DELETETable() {
		$table = uniqid('test:');
		$this->assertEquals('DELETE FROM "' . $table .'" ', $this->driver->_DELETE($table));
	}
}
