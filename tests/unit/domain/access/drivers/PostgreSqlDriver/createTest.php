<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class createTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_CREATEEmptyFields() {
		$this->assertEquals('', $this->driver->_CREATE(''));
	}

	public function test_CREATETable() {
		$table = uniqid('test:');
		$this->assertEquals('CREATE TABLE "' . $table .'" ', $this->driver->_CREATE($table));
	}
}
