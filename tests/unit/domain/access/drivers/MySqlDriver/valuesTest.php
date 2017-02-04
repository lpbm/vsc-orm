<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class valuesTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_EmptyValues() {
		$this->assertEquals('', $this->driver->_VALUES(''));
	}

	public function test_Values() {
		$val = uniqid('test:');
		$this->assertEquals(' VALUES ' . $val, $this->driver->_VALUES($val));
	}
}
