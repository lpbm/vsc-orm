<?php
namespace test\domain\access\drivers\PostgreSqlDriver;

use orm\domain\access\drivers\PostgreSqlDriver;

class joinTest extends \BaseTestCase
{
	/**
	 * @var PostgreSqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new PostgreSqlDriver();
	}

	public function test_JOINEmptyFields() {
		$this->assertEquals('', $this->driver->_JOIN(''));
	}

	public function test_JOINTable() {
		$type = uniqid('test:');
		$this->assertEquals($type .' JOIN ', $this->driver->_JOIN($type));
	}
}
