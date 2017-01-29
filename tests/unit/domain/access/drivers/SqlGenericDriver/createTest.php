<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class createTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_CREATEEmptyFields() {
		$this->assertEquals('', $this->driver->_CREATE(''));
	}

	public function test_CREATETable() {
		$table = uniqid('test:');
		$this->assertEquals('CREATE TABLE ' . $table .' ', $this->driver->_CREATE($table));
	}
}
