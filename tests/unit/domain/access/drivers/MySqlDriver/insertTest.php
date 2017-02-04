<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class insertTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_INSERTEmptyFields() {
		$this->assertEquals('', $this->driver->_INSERT(''));
	}

	public function test_INSERTTable() {
		$table = uniqid('test:');
		$this->assertEquals('INSERT INTO ' . $table .' ', $this->driver->_INSERT($table));
	}
}
