<?php
namespace test\domain\access\drivers\SqlGenericDriver;

use orm\domain\access\drivers\SqlGenericDriver;

class joinTest extends \BaseTestCase
{
	/**
	 * @var SqlGenericDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new SqlGenericDriver();
	}

	public function test_JOINEmptyFields() {
		$this->assertEquals('', $this->driver->_JOIN(''));
	}

	public function test_JOINTable() {
		$type = uniqid('test:');
		$this->assertEquals($type .' JOIN ', $this->driver->_JOIN($type));
	}
}
