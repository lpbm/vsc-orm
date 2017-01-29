<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class insertTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_INSERTEmptyFields() {
		$this->assertEquals('', $this->driver->_INSERT(''));
	}

	public function test_INSERTTable() {
		$table = uniqid('test:');
		$this->assertEquals('INSERT INTO ' . $table .' ', $this->driver->_INSERT($table));
	}
}
