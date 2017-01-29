<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class updateTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_UPDATENoTable() {
		$this->assertEquals('', $this->driver->_UPDATE(''));
	}

	public function test_UPDATETable() {
		$table = uniqid('test:');
		$this->assertEquals('UPDATE ' . $table .' ', $this->driver->_UPDATE($table));
	}
}
