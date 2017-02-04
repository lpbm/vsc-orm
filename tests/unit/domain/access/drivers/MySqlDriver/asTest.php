<?php
namespace test\domain\access\drivers\MySqlDriver;

use orm\domain\access\drivers\MySqlDriver;

class asTest extends \BaseTestCase
{
	/**
	 * @var MySqlDriver
	 */
	protected $driver;

	public function setUp() {
		$this->driver = new MySqlDriver();
	}

	public function test_ASEmptyFields() {
		$this->assertEquals('', $this->driver->_AS(''));
	}

	public function test_ASTable() {
		$alias = uniqid('test:');
		$this->assertEquals(' AS ' . $alias, $this->driver->_AS($alias));
	}
}
