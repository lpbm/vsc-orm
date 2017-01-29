<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class deleteTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_DELETEEmptyFields() {
		$this->assertEquals('', $this->driver->_DELETE(''));
	}

	public function test_DELETETable() {
		$table = uniqid('test:');
		$this->assertEquals('DELETE FROM ' . $table .' ', $this->driver->_DELETE($table));
	}
}
