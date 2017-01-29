<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class valuesTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_EmptyValues() {
		$this->assertEquals('', $this->driver->_VALUES(''));
	}

	public function test_Values() {
		$val = uniqid('test:');
		$this->assertEquals(' VALUES ' . $val, $this->driver->_VALUES($val));
	}
}
