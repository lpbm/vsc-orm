<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class selectTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_SELECTEmptyFields() {
		$this->assertEquals('', $this->driver->_SELECT(''));
	}

	public function test_SELECTSingleField() {
		$field = uniqid('test:');
		$this->assertEquals('SELECT ' . $field .' ', $this->driver->_SELECT($field));
	}

	public function test_SELECTMultipleFields() {
		$field1 = uniqid('test1:');
		$field2 = uniqid('test2:');
		$field3 = uniqid('test4:');
		$multi = [$field1, $field2, $field3];
		$this->assertEquals('SELECT ' . implode(', ', $multi) . ' ', $this->driver->_SELECT($multi));
	}
}
