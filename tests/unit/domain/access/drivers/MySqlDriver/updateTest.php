<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class updateTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_UPDATENoTable() {
		$this->assertEquals('', $this->driver->_UPDATE(''));
	}

	public function test_UPDATETable() {
		$table = uniqid('test:');
		$this->assertEquals('UPDATE ' . $table .' ', $this->driver->_UPDATE($table));
	}
}
