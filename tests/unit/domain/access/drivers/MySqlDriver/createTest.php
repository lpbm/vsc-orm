<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class createTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_CREATEEmptyFields() {
		$this->assertEquals('', $this->driver->_CREATE(''));
	}

	public function test_CREATETable() {
		$table = uniqid('test:');
		$this->assertEquals('CREATE TABLE ' . $table .' ', $this->driver->_CREATE($table));
	}
}
