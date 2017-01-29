<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class fromTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_EmptyValues() {
		$this->assertEquals('', $this->driver->_FROM(''));
	}

	public function test_Values() {
		$val = uniqid('test:');
		$this->assertEquals(' FROM ' . $val . ' ', $this->driver->_FROM($val));
	}

	public function test_FROMWithMultipleData() {
		$field1 = uniqid('test1:');
		$field2 = uniqid('test2:');
		$field3 = uniqid('test4:');
		$multi = [$field1, $field2, $field3];
		$this->assertEquals(' FROM ' . implode(",\n", $multi) . ' ', $this->driver->_FROM($multi));
	}
}
