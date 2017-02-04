<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class deleteTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_DELETEEmptyFields() {
		$this->assertEquals('', $this->driver->_DELETE(''));
	}

	public function test_DELETETable() {
		$table = uniqid('test:');
		$this->assertEquals('DELETE FROM ' . $table .' ', $this->driver->_DELETE($table));
	}
}
