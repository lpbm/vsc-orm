<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class updateTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_UPDATENoTable() {
		$this->assertEquals('', $this->driver->_UPDATE(''));
	}

	public function test_UPDATETable() {
		$table = uniqid('test:');
		$this->assertEquals('UPDATE "' . $table .'" ', $this->driver->_UPDATE($table));
	}
}
