<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class valuesTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_EmptyValues() {
		$this->assertEquals('', $this->driver->_VALUES(''));
	}

	public function test_Values() {
		$val = uniqid('test:');
		$this->assertEquals(' VALUES ' . $val, $this->driver->_VALUES($val));
	}
}
