<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class joinTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_JOINEmptyFields() {
		$this->assertEquals('', $this->driver->_JOIN(''));
	}

	public function test_JOINTable() {
		$type = uniqid('test:');
		$this->assertEquals($type .' JOIN ', $this->driver->_JOIN($type));
	}
}
