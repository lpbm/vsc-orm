<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class insertTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_INSERTEmptyFields() {
		$this->assertEquals('', $this->driver->_INSERT(''));
	}

	public function test_INSERTTable() {
		$table = uniqid('test:');
		$this->assertEquals('INSERT INTO "' . $table .'" ', $this->driver->_INSERT($table));
	}
}
